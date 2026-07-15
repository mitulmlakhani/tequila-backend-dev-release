#!/usr/bin/env bash
# =============================================================================
# Tequila POS – Script to automate adding custom domains to /etc/hosts.
# Run this on your host machine: sudo bash docker/setup-hosts.sh
# =============================================================================

set -uo pipefail

DOMAINS=(
  "tequilapos.local"
  "echo.tequilapos.local"
)

IP="127.0.0.1"
HOSTS_FILE="/etc/hosts"

echo "🔍 Checking /etc/hosts entries..."
MISSING_DOMAINS=()

for domain in "${DOMAINS[@]}"; do
  if grep -q "$domain" "$HOSTS_FILE" 2>/dev/null; then
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
echo "Adding missing domains to $HOSTS_FILE:"
FAILED=0
for domain in "${MISSING_DOMAINS[@]}"; do
  echo "  Adding: $IP $domain"
  if [ "$(id -u)" -eq 0 ]; then
    echo "$IP $domain" >> "$HOSTS_FILE" || { echo "  ⚠️  Failed to write $domain to $HOSTS_FILE"; FAILED=1; }
  else
    echo "$IP $domain" | sudo tee -a "$HOSTS_FILE" > /dev/null || { echo "  ⚠️  Failed to write $domain (sudo required)"; FAILED=1; }
  fi
done

if [ "$FAILED" -eq 0 ]; then
  echo "✅ Successfully updated $HOSTS_FILE!"
else
  echo "⚠️  Some entries could not be added. Please add them manually:"
  for domain in "${MISSING_DOMAINS[@]}"; do
    echo "    echo \"$IP $domain\" | sudo tee -a $HOSTS_FILE"
  done
fi
