#!/usr/bin/env bash
# =============================================================================
# Tequila POS – Script to automate adding custom domains to /etc/hosts.
# Run this on your host machine: bash .lando/setup-hosts.sh
# =============================================================================
set -euo pipefail

DOMAINS=(
  "tequilapos.local"
  "echo.tequilapos.local"
  "adminer.tequilapos.local"
)

IP="127.0.0.1"
HOSTS_FILE="/etc/hosts"

echo "🔍 Checking /etc/hosts entries..."
MISSING_DOMAINS=()

for domain in "${DOMAINS[@]}"; do
  # Check if domain is already present in hosts file
  if grep -qE "\s+$domain\s*$" "$HOSTS_FILE" || grep -qE "^$domain\s*$" "$HOSTS_FILE" || grep -q "$domain" "$HOSTS_FILE"; then
    echo "  ✅ $domain is already in $HOSTS_FILE"
  else
    echo "  ❌ $domain is missing"
    MISSING_DOMAINS+=("$domain")
  fi
done

if [ ${#MISSING_DOMAINS[@]} -eq 0 ]; then
  echo "🎉 All domains are already configured in $HOSTS_FILE!"
  exit 0
fi

echo ""
echo "Adding missing domains to $HOSTS_FILE (requires sudo permissions):"
for domain in "${MISSING_DOMAINS[@]}"; do
  echo "  Adding: $IP $domain"
  # Safely append using sudo and tee
  echo "$IP $domain" | sudo tee -a "$HOSTS_FILE" > /dev/null
done

echo "✅ Successfully updated $HOSTS_FILE!"
