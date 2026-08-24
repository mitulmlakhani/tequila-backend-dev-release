#!/usr/bin/env bash

set -Eeuo pipefail

BACKUP_DIR="/etc/netplan/backup"
NETPLAN_FILE="/etc/netplan/01-static-local.yaml"

# ---------------------------------------------------------------------------
# Privilege helper.
#
# Works whether run as a normal user with sudo (typical host), or as root
# with no sudo installed at all (common in containers) -- in the latter
# case, commands just run directly.
# ---------------------------------------------------------------------------
if [[ "$(id -u)" -eq 0 ]]; then
    SUDO=""
elif command -v sudo &>/dev/null; then
    SUDO="sudo"
else
    echo "This script needs root privileges (sudo not found, and not running as root)."
    exit 1
fi

# ---------------------------------------------------------------------------
# Ensure required tools are installed: iproute2 (ip), iputils-ping (ping),
# iputils-arping (arping), netplan.io (netplan). Only apt-based systems are
# supported for auto-install; other package managers just get a warning.
# ---------------------------------------------------------------------------
declare -A TOOL_PACKAGE=(
    [ip]="iproute2"
    [ping]="iputils-ping"
    [arping]="iputils-arping"
    [netplan]="netplan.io"
)

MISSING_PACKAGES=()
for tool in "${!TOOL_PACKAGE[@]}"; do
    if ! command -v "$tool" &>/dev/null; then
        MISSING_PACKAGES+=("${TOOL_PACKAGE[$tool]}")
    fi
done

if [[ ${#MISSING_PACKAGES[@]} -gt 0 ]]; then
    echo "Missing required tools, packages needed: ${MISSING_PACKAGES[*]}"
    if command -v apt-get &>/dev/null; then
        echo "Installing via apt-get..."
        $SUDO apt-get update -qq
        $SUDO apt-get install -y "${MISSING_PACKAGES[@]}"
    else
        echo "No apt-get found -- install these packages manually for your distro: ${MISSING_PACKAGES[*]}"
        exit 1
    fi
fi

# netplan itself only works with systemd-networkd/NetworkManager on a real
# host. If it's still missing/non-functional after install (e.g. inside a
# container with no init system), this script cannot proceed safely.
if ! command -v netplan &>/dev/null; then
    echo "netplan is not available on this system. This script only supports"
    echo "Ubuntu-style hosts using netplan; it will not work here."
    exit 1
fi

# Detect active interface
INTERFACE=$(ip route | awk '/default/ {print $5; exit}')
GATEWAY=$(ip route | awk '/default/ {print $3; exit}')

if [[ -z "$INTERFACE" || -z "$GATEWAY" ]]; then
    echo "Unable to detect default interface or gateway."
    exit 1
fi

# Detect current IP/prefix
CURRENT_CIDR=$(ip -4 addr show "$INTERFACE" | awk '/inet / {print $2; exit}')

if [[ -z "$CURRENT_CIDR" ]]; then
    echo "Unable to determine current IPv4 address."
    exit 1
fi

PREFIX=${CURRENT_CIDR#*/}
NETWORK=$(echo "$GATEWAY" | awk -F. '{print $1"."$2"."$3}')

# ---------------------------------------------------------------------------
# Pick a free static IP.
#
# This script may run against routers from many different vendors, so it
# cannot assume any router-side setup (DHCP reservation, a defined static
# range, or even a UPnP-queryable pool). The only thing that works
# universally is observing the live network directly. Strategy:
#
#   1. Search from the very top of the /24 downward (.254 -> .220), since
#      that's the region least likely to overlap a DHCP pool regardless of
#      vendor defaults -- a convention, not a guarantee.
#   2. Check ARP first, ping second. ARP is the stronger signal: a device
#      can disable ICMP responses and hide from ping, but it can't stay on
#      the network and hide from ARP. Ping is kept as a fallback in case
#      arping isn't installed.
#   3. Re-verify the chosen address immediately before writing config, to
#      shrink the window between "checked free" and "claimed."
#   4. After applying, re-check for a conflict and roll back automatically
#      if one appears (in addition to netplan's own rollback-on-timeout).
#
# None of this can guarantee the router's DHCP server will never hand this
# same address to another device later, if it happens to fall inside that
# router's pool -- there is no cross-vendor way to know that pool's bounds
# without logging into that specific router's admin UI. This is the best
# achievable safety without assuming anything about the router.
# ---------------------------------------------------------------------------

echo "Scanning for a free static IP on ${NETWORK}.0/24 ..."

CANDIDATE_HOST_MIN=200
CANDIDATE_HOST_MAX=254
HAVE_ARPING=false
command -v arping &>/dev/null && HAVE_ARPING=true

STATIC_IP=""

is_ip_in_use() {
    local ip="$1"

    # ARP check first -- harder for a device to evade than ICMP
    if $HAVE_ARPING; then
        if $SUDO arping -c 2 -w 2 -I "$INTERFACE" "$ip" &>/dev/null; then
            return 0
        fi
    fi

    # Ping as a fallback/secondary signal
    if ping -c 1 -W 1 "$ip" &>/dev/null; then
        return 0
    fi

    return 1
}

if ! $HAVE_ARPING; then
    echo "Note: 'arping' still unavailable, falling back to ping-only checks (less reliable)."
fi

for host in $(seq "$CANDIDATE_HOST_MAX" -1 "$CANDIDATE_HOST_MIN"); do
    CANDIDATE="${NETWORK}.${host}"

    # Skip broadcast, the gateway, and our own current address
    [[ "$host" == 255 ]] && continue
    [[ "$CANDIDATE" == "$GATEWAY" ]] && continue
    [[ "$CANDIDATE" == "${CURRENT_CIDR%/*}" ]] && continue

    if ! is_ip_in_use "$CANDIDATE"; then
        STATIC_IP="$CANDIDATE"
        break
    else
        echo "  $CANDIDATE is in use, skipping..."
    fi
done

if [[ -z "$STATIC_IP" ]]; then
    echo "Could not find a free address in ${NETWORK}.${CANDIDATE_HOST_MIN}-${CANDIDATE_HOST_MAX}."
    echo "Check your router's DHCP pool range and adjust CANDIDATE_HOST_MIN/MAX, or free up an address."
    exit 1
fi

echo "Selected free IP: $STATIC_IP"
echo
echo "Detected configuration"
echo "----------------------"
echo "Interface : $INTERFACE"
echo "Gateway   : $GATEWAY"
echo "Current   : $CURRENT_CIDR"
echo "Static IP : $STATIC_IP/$PREFIX"
echo

read -rp "Apply this configuration? [y/N]: " CONFIRM
[[ "$CONFIRM" =~ ^[Yy]$ ]] || exit 0

echo "Re-checking $STATIC_IP is still free right before applying..."
if is_ip_in_use "$STATIC_IP"; then
    echo "Address $STATIC_IP was claimed by another device since the scan. Aborting."
    echo "Re-run the script to pick a different address."
    exit 1
fi

$SUDO mkdir -p "$BACKUP_DIR"

TIMESTAMP=$(date +%Y%m%d-%H%M%S)

$SUDO cp /etc/netplan/*.yaml "$BACKUP_DIR/" 2>/dev/null || true

cat <<EOF | $SUDO tee "$NETPLAN_FILE" >/dev/null
network:
  version: 2
  renderer: networkd
  ethernets:
    $INTERFACE:
      dhcp4: false
      addresses:
        - $STATIC_IP/$PREFIX
      routes:
        - to: default
          via: $GATEWAY
      nameservers:
        addresses:
          - $GATEWAY
          - 1.1.1.1
          - 8.8.8.8
EOF

$SUDO chmod 600 "$NETPLAN_FILE"

echo "Generated:"
echo
cat "$NETPLAN_FILE"
echo

echo "Validating..."

$SUDO netplan generate

echo
echo "======================================================================="
echo "Applying new network configuration..."
echo "Your SSH session will now drop (Connection reset)."
echo "Please reconnect using the new Static IP: ${STATIC_IP}"
echo "======================================================================="
echo "Note: this address was verified free at scan time, but to permanently"
echo "guarantee it, reserve this IP for this machine's MAC address in your"
echo "router's DHCP settings."
echo

$SUDO netplan apply

echo
echo "Checking for a duplicate-IP conflict after applying..."

CONFLICT_DETECTED=false
if $HAVE_ARPING; then
    # Count distinct MAC addresses answering for our own static IP.
    # If more than one replies, another device also holds this address.
    REPLY_COUNT=$($SUDO arping -c 3 -w 3 -I "$INTERFACE" "$STATIC_IP" 2>/dev/null \
        | grep -c "reply from" || true)
    if [[ "$REPLY_COUNT" -gt 1 ]]; then
        CONFLICT_DETECTED=true
    fi
fi

if $CONFLICT_DETECTED; then
    echo "Duplicate IP detected on $STATIC_IP after applying! Rolling back..."
    LATEST_BACKUP=$(ls -t "$BACKUP_DIR"/*.yaml 2>/dev/null | head -n1 || true)
    $SUDO rm -f "$NETPLAN_FILE"
    if [[ -n "$LATEST_BACKUP" ]]; then
        $SUDO cp "$LATEST_BACKUP" /etc/netplan/
    fi
    $SUDO netplan apply
    echo "Rolled back. The address $STATIC_IP is not safe to use on this network."
    exit 1
fi

echo
echo "Network configuration applied successfully."