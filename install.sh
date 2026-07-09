#!/usr/bin/env bash
# =============================================================================
# Tequila POS - One-shot installer
# Usage: ./install.sh
#        (you will be prompted for RESTAURANT_ID and CLOUD_API_AUTH_KEY)
# =============================================================================
set -uo pipefail

read -rp "Enter RESTAURANT_ID: " RESTAURANT_ID
read -rp "Enter CLOUD_API_AUTH_KEY: " CLOUD_API_AUTH_KEY

if [ -z "$RESTAURANT_ID" ] || [ -z "$CLOUD_API_AUTH_KEY" ]; then
  echo "❌ Both RESTAURANT_ID and CLOUD_API_AUTH_KEY are required."
  exit 1
fi

LANDO_YML=".lando.yml"
SUPERVISOR_CONF=".lando/supervisord.conf"
SETUP_HOSTS=".lando/setup-hosts.sh"
TRUST_CERTS=".lando/trust-certs.sh"

# Portable in-place sed (works on both GNU/Linux and BSD/macOS)
sedi() {
  sed -i.bak "$1" "$2" && rm -f "$2.bak"
}

echo "🔧 Updating RESTAURANT_ID / CLOUD_API_AUTH_KEY..."

# --- .lando.yml (YAML style:  KEY: "value") ---------------------------------
sedi "s/RESTAURANT_ID: \"[^\"]*\"/RESTAURANT_ID: \"${RESTAURANT_ID}\"/" "$LANDO_YML"
sedi "s/CLOUD_API_AUTH_KEY: \"[^\"]*\"/CLOUD_API_AUTH_KEY: \"${CLOUD_API_AUTH_KEY}\"/" "$LANDO_YML"

# --- supervisord.conf (ini/env style:  KEY="value") --------------------------
sedi "s/RESTAURANT_ID=\"[^\"]*\"/RESTAURANT_ID=\"${RESTAURANT_ID}\"/g" "$SUPERVISOR_CONF"
sedi "s/CLOUD_API_AUTH_KEY=\"[^\"]*\"/CLOUD_API_AUTH_KEY=\"${CLOUD_API_AUTH_KEY}\"/g" "$SUPERVISOR_CONF"

echo "✅ Config updated."

# --- Start Lando --------------------------------------------------------------
echo "🚀 Starting Lando..."
lando start

# --- Hosts file on host machine ----------------------------------------------
echo "🌐 Configuring /etc/hosts on host machine..."
sudo bash "$SETUP_HOSTS"

# --- Hosts file inside appserver -----------------------------------------------
echo "🌐 Configuring /etc/hosts inside appserver..."
lando ssh -c "bash /app/.lando/setup-hosts.sh"

# --- Trust local CA certs -------------------------------------------------------
echo "🔒 Trusting Lando SSL certificates..."
bash "$TRUST_CERTS"

# --- Cron job for daily auto-update ---------------------------------------------
echo "⏰ Registering daily auto-update cron job..."
PROJECT_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
UPDATOR="$PROJECT_PATH/updator.sh"
chmod +x "$UPDATOR"

CRON_PATH_LINE="PATH=/usr/local/bin:/usr/local/sbin:/usr/bin:/bin"
CRON_JOB="0 5 * * * ${UPDATOR} >> ${PROJECT_PATH}/storage/logs/cron.log 2>&1"

# Rebuild crontab: strip any previous PATH line + previous updator.sh entry,
# then re-add both fresh, so re-running install.sh doesn't create duplicates.
{
  crontab -l 2>/dev/null | grep -v "^PATH=" | grep -v "updator.sh"
  echo "$CRON_PATH_LINE"
  echo "$CRON_JOB"
} | crontab -

echo "✅ Cron job installed: runs daily at 05:00 -> $UPDATOR"

echo ""
echo "🎉 ======================================================= 🎉"
echo "✅  TEQUILA POS PROJECT SETUP COMPLETE!"
echo "   🌮  App URL:  https://tequilapos.local"
echo "   🌮   DB URL:  https://adminer.tequilapos.local"
echo "   🌮   DB User:  root"
echo "   🌮   DB Password:  tequila"
echo "   🌮   DB Name:  tequila_pos"
echo "   🌮  Socket URL:  https://echo.tequilapos.local"
if [ "$HEALTH_OK" = false ]; then
    echo ""
    echo "   ⚠️  Some health checks didn't pass."
    echo "   If this is a fresh install, run:  lando fresh"
    echo "   Then retry:  lando worker-status"
fi
echo "🎉 ======================================================= 🎉"
echo ""