#!/usr/bin/env bash

set -Eeuo pipefail

BACKUP_DIR="/etc/netplan/backup"
NETPLAN_FILE="/etc/netplan/01-static-local.yaml"

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
STATIC_IP="${NETWORK}.200"

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

sudo mkdir -p "$BACKUP_DIR"

TIMESTAMP=$(date +%Y%m%d-%H%M%S)

sudo cp /etc/netplan/*.yaml "$BACKUP_DIR/" 2>/dev/null || true

cat <<EOF | sudo tee "$NETPLAN_FILE" >/dev/null
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

echo "Generated:"
echo
cat "$NETPLAN_FILE"
echo

echo "Validating..."

sudo netplan generate

echo
echo "Applying with rollback protection..."

sudo netplan try --timeout 120

echo
echo "Network configuration applied successfully."
echo "Allocated Static IP: ${STATIC_IP}"