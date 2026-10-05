#!/usr/bin/env bash
# =============================================================================
# Tequila POS - One-shot installer (Docker)
# Usage: ./install.sh
#        (you will be prompted for RESTAURANT_ID and CLOUD_API_AUTH_KEY)
# =============================================================================
set -uo pipefail


### Update to Latest Release
echo "Checking latest release..."

git fetch --tags --quiet --force

LATEST_TAG=$(git ls-remote --tags origin | awk -F/ '!/\^\{\}$/ {print $3}' | sort -V | tail -n1)

CURRENT_TAG=$(git describe --tags --exact-match 2>/dev/null || true)

echo "LATEST_TAG: $LATEST_TAG, CURRENT_TAG: $CURRENT_TAG"

if [ "$CURRENT_TAG" != "$LATEST_TAG" ] && [ -n "$LATEST_TAG" ]; then
    echo "Updating to latest release."

    echo "Updating $CURRENT_TAG -> $LATEST_TAG"

    # Fetch ONLY the new tag
    git fetch --depth=1 origin tag "$LATEST_TAG"

    git checkout -f "$LATEST_TAG"
fi

## Prompt for Required Environment Variables

read -rp "Enter RESTAURANT_ID: " RESTAURANT_ID
read -rp "Enter CLOUD_API_AUTH_KEY: " CLOUD_API_AUTH_KEY
read -rp "Enter CLOUD_URL [https://tequilapos.net]: " CLOUD_URL
CLOUD_URL="${CLOUD_URL:-https://tequilapos.net}"

if [ -z "$RESTAURANT_ID" ] || [ -z "$CLOUD_API_AUTH_KEY" ]; then
  echo "❌ Both RESTAURANT_ID and CLOUD_API_AUTH_KEY are required."
  exit 1
fi

DOCKER_COMPOSE_YML="docker-compose.yml"
SUPERVISOR_CONF="docker/supervisord.conf"
SETUP_HOSTS="docker/setup-hosts.sh"

# Portable in-place sed (works on both GNU/Linux and BSD/macOS)
sedi() {
  sed -i.bak "$1" "$2" && rm -f "$2.bak"
}

echo "🔧 Updating RESTAURANT_ID / CLOUD_API_AUTH_KEY / CLOUD_URL..."

# --- docker-compose.yml (YAML style:  KEY: "value") --------------------------
sedi "s/RESTAURANT_ID: \"[^\"]*\"/RESTAURANT_ID: \"${RESTAURANT_ID}\"/" "$DOCKER_COMPOSE_YML"
sedi "s/CLOUD_API_AUTH_KEY: \"[^\"]*\"/CLOUD_API_AUTH_KEY: \"${CLOUD_API_AUTH_KEY}\"/" "$DOCKER_COMPOSE_YML"
sedi "s|CLOUD_URL: \"[^\"]*\"|CLOUD_URL: \"${CLOUD_URL}\"|" "$DOCKER_COMPOSE_YML"

# --- supervisord.conf (ini/env style:  KEY="value") --------------------------
sedi "s/RESTAURANT_ID=\"[^\"]*\"/RESTAURANT_ID=\"${RESTAURANT_ID}\"/g" "$SUPERVISOR_CONF"
sedi "s/CLOUD_API_AUTH_KEY=\"[^\"]*\"/CLOUD_API_AUTH_KEY=\"${CLOUD_API_AUTH_KEY}\"/g" "$SUPERVISOR_CONF"
sedi "s|CLOUD_URL=\"[^\"]*\"|CLOUD_URL=\"${CLOUD_URL}\"|g" "$SUPERVISOR_CONF"

echo "✅ Config updated."

# --- Prepare storage and bootstrap directory structure & permissions ---------
echo "🔒 Preparing storage and bootstrap/cache permissions on host..."
mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 777 storage bootstrap/cache 2>/dev/null || sudo chmod -R 777 storage bootstrap/cache 2>/dev/null || true

# --- Start Docker -------------------------------------------------------------
echo "🚀 Starting Docker containers..."
docker compose up -d --build

# --- Ensure permissions inside container after start --------------------------
echo "🔒 Fixing storage and bootstrap/cache directory permissions across host and container..."
chmod -R 777 storage bootstrap/cache 2>/dev/null || sudo chmod -R 777 storage bootstrap/cache 2>/dev/null || true
docker compose exec -T appserver bash -c "mkdir -p storage/app/public storage/framework/{cache/data,sessions,views} storage/logs bootstrap/cache && chown -R www-data:www-data storage bootstrap/cache 2>/dev/null && chmod -R 777 storage bootstrap/cache 2>/dev/null" || true

# --- Hosts file on host machine ----------------------------------------------
echo "🌐 Configuring /etc/hosts on host machine..."
sudo bash "$SETUP_HOSTS"

# --- Cron job for daily auto-update ---------------------------------------------
echo "⏰ Registering daily auto-update cron job..."
PROJECT_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
UPDATOR="$PROJECT_PATH/updator.sh"
chmod +x "$UPDATOR"

CRON_PATH_LINE="PATH=/usr/local/bin:/usr/local/sbin:/usr/bin:/bin"
CRON_JOB="0 5 * * * ${UPDATOR} >> ${PROJECT_PATH}/storage/logs/cron.log 2>&1"

# Rebuild crontab: strip any previous PATH line + previous updator entries,
# then re-add both fresh, so re-running install.sh doesn't create duplicates.
{
  crontab -l 2>/dev/null | grep -v "^PATH=" | grep -v "updator.*\.sh"
  echo "$CRON_PATH_LINE"
  echo "$CRON_JOB"
} | crontab -

echo "✅ Cron job installed: runs daily at 05:00 -> $UPDATOR"

echo ""
echo "🎉 ======================================================= 🎉"
echo "✅  TEQUILA POS PROJECT SETUP COMPLETE!"
echo "   🌮  App URL:     http://localhost (or http://tequilapos.local)"
echo "   🌮  Socket URL:  http://localhost:6001 (or http://echo.tequilapos.local:6001)"
echo "   🌮  DB Host:     localhost:3306"
echo "   🌮  DB User:     tequila (root password: tequila)"
echo "   🌮  DB Name:     tequila_pos"
echo "   🌮  Redis Host:  localhost:63790"
echo "🎉 ======================================================= 🎉"
echo ""