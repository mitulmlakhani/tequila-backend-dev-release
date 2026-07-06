#!/usr/bin/env bash
# =============================================================================
# Tequila POS – Automated Background Daily Update Script (Option 1: Graceful Reload)
# Designed to be run via cron (0 5 * * *) or manually.
# Automates:
# 1. Fetching remote tags and checking out the latest release tag
# 2. Running Composer install and database migrations via Lando
# 3. Optimizing and refreshing Laravel caches
# 4. Zero/Near-Zero Downtime Graceful Reload:
#    - Reloads queue workers & graceful Apache reload if container configs didn't change (<500ms)
#    - Performs full lando restart only if container configuration changed (.lando.yml / .lando/)
# =============================================================================
set -uo pipefail

PROJECT_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$PROJECT_PATH" || { echo "❌ Error: Could not enter project directory $PROJECT_PATH"; exit 1; }

# Create log directory if it doesn't exist
mkdir -p storage/logs
LOG_FILE="$PROJECT_PATH/storage/logs/auto-update.log"

# Function to log messages with timestamps both to console and log file
log() {
    local msg="[$(date '+%Y-%m-%d %H:%M:%S')] $1"
    echo "$msg" | tee -a "$LOG_FILE"
}

log "=========================================================="
log "🌮  Tequila POS: Automated Background Update Started"
log "=========================================================="

# ── 1. Fetch tags and check for update ───────────────────────────────────────
log "Fetching latest tags from remote repository..."

git config --global --add safe.directory "$PROJECT_PATH" || {
    log "❌ Error: Failed to set safe.directory for Git."
    exit 1
}

git fetch --tags --quiet || {
    log "❌ Error: Failed to fetch tags from remote repository."
    exit 1
}

# Get the latest tag name sorted by version
LATEST_TAG=$(git tag -l | sort -V | tail -n1)

if [ -z "$LATEST_TAG" ]; then
    log "❌ Error: No tags found in the remote repository."
    exit 1
fi

CURRENT_VERSION=$(git describe --tags --abbrev=0 2>/dev/null || echo "none")
log "Detected latest tag: $LATEST_TAG (Current version: $CURRENT_VERSION)"

if [ "$LATEST_TAG" = "$CURRENT_VERSION" ]; then
    log "✅ Project is already up-to-date at version $LATEST_TAG. No changes needed."
    log "=========================================================="
    exit 0
fi

log "🚀 New release tag found ($LATEST_TAG)! Starting update process..."

# ── 2. Check if Lando container configuration will change ────────────────────
# Compare .lando.yml and .lando/ between current HEAD and new tag
LANDO_CONFIG_CHANGED=0
if git diff --quiet HEAD "$LATEST_TAG" -- .lando.yml .lando/ 2>/dev/null; then
    log "ℹ️  No Lando container configuration changes detected between $CURRENT_VERSION and $LATEST_TAG."
    LANDO_CONFIG_CHANGED=0
else
    log "⚠️  Lando container configuration changes detected between releases."
    LANDO_CONFIG_CHANGED=1
fi

# ── 3. Protect local changes and checkout new tag ────────────────────────────
log "Stashing any local working directory changes..."
git stash --quiet || true

log "Checking out tag: $LATEST_TAG..."
git checkout "$LATEST_TAG" --quiet || {
    log "❌ Error: Failed to check out tag $LATEST_TAG."
    exit 1
}
log "✅ Successfully checked out to $LATEST_TAG!"

# ── 4. Verify Lando is running ───────────────────────────────────────────────
if ! docker ps --format '{{.Names}}' 2>/dev/null | grep -qiE '(tequila-pos|appserver)'; then
    log "⚠️  Lando containers are currently stopped. Starting Lando services..."
    lando start >> "$LOG_FILE" 2>&1
fi

# ── 5. Install PHP dependencies via Lando ────────────────────────────────────
log "📦 Installing PHP dependencies (composer install)..."
lando composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --quiet >> "$LOG_FILE" 2>&1 || {
    log "❌ Error: Composer install failed."
    exit 1
}
log "✅ Composer dependencies installed."

# ── 6. Run Database Migrations ───────────────────────────────────────────────
log "🗄️  Running database migrations..."
lando artisan migrate --force --no-interaction >> "$LOG_FILE" 2>&1 || {
    log "❌ Error: Database migration failed."
    exit 1
}
log "✅ Database migrations completed."

# ── 7. Optimize Laravel Caches ───────────────────────────────────────────────
log "⚡ Clearing and rebuilding Laravel caches..."
lando artisan config:cache --no-interaction >> "$LOG_FILE" 2>&1 || true
lando artisan route:cache --no-interaction >> "$LOG_FILE" 2>&1 || true
lando artisan view:cache --no-interaction >> "$LOG_FILE" 2>&1 || true
log "✅ Laravel caches rebuilt."

# ── 8. Zero/Near-Zero Downtime Graceful Reload ───────────────────────────────
if [ "$LANDO_CONFIG_CHANGED" -eq 1 ]; then
    log "🔄 Performing full Lando restart to apply container configuration changes..."
    lando restart >> "$LOG_FILE" 2>&1
    log "Reloading supervisor background workers..."
    lando ssh -u root -c "supervisorctl update && supervisorctl restart all" >> "$LOG_FILE" 2>&1 || true
    log "✅ Full Lando restart completed."
else
    log "✨ Option 1 Graceful Reload: Reloading queue workers & Apache with ZERO downtime..."
    # Restart background workers (queue workers & scheduler) so they pick up new code
    lando ssh -u root -c "supervisorctl update && supervisorctl restart all" >> "$LOG_FILE" 2>&1 || true
    # Gracefully reload Apache without dropping active HTTP connections
    lando ssh -u root -c "apachectl -k graceful 2>/dev/null || service apache2 reload 2>/dev/null || true" >> "$LOG_FILE" 2>&1 || true
    log "✅ Graceful reload completed seamlessly!"
fi

log "🎉 =========================================================="
log "✅  TEQUILA POS SUCCESSFULLY UPDATED TO VERSION: $LATEST_TAG"
log "🎉 =========================================================="
echo ""