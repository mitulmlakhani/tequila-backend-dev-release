#!/bin/bash

# 1. Detect active network details
INTERFACE=$(ip route show default | awk '{print $5}')
ROUTER_IP=$(ip route show default | awk '{print $3}')

if [ -z "$INTERFACE" ] || [ -z "$ROUTER_IP" ]; then
    echo "Error: Active network interface or router IP not found."
    exit 1
fi

BASE_IP=$(echo "$ROUTER_IP" | cut -d'.' -f1-3)
STATIC_IP="$BASE_IP.200"

echo "=== Detected Network Configurations ==="
echo "Network Interface: $INTERFACE"
echo "Router Gateway IP: $ROUTER_IP"
echo "Target Static IP:  $STATIC_IP"
echo "======================================="

# 2. Back up all current configurations safely
sudo mkdir -p /etc/netplan/backup
sudo cp /etc/netplan/*.yaml /etc/netplan/backup/ 2>/dev/null

# 3. Create the perfect Netplan YAML syntax
sudo bash -c "cat << 'EOF' > /etc/netplan/01-static-local.yaml
network:
  version: 2
  renderer: networkd
  ethernets:
    $INTERFACE:
      dhcp4: no
      addresses:
        - $STATIC_IP/24
      routes:
        - to: default
          via: $ROUTER_IP
      nameservers:
        addresses:
EOF"

# 4. Clean up old configuration files to prevent IP conflicts
sudo rm /etc/netplan/50-cloud-init.yaml /etc/netplan/00-installer-config.yaml 2>/dev/null

# 5. Try the changes safely with a fallback timeout
echo "Testing configuration... If your terminal freezes, wait 60 seconds for an auto-rollback."
sudo netplan try --timeout 60
