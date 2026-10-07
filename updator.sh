#!/usr/bin/env bash
# =============================================================================
# Tequila POS – Automated Background Daily Update Script (Docker)
# Designed to be run via cron (0 5 * * *) or manually.
# Automates:
# 1. Fetching remote tags and checking out the latest release tag
# 2. Running Composer install and database migrations via Docker Compose
# 3. Optimizing and refreshing Laravel caches
# 4. Zero/Near-Zero Downtime Graceful Reload:
#    - Reloads queue workers & graceful Apache reload if container configs didn't change (<500ms)
#    - Performs full Docker build/restart only if container configuration changed (docker-compose.yml / docker/)
# =============================================================================
set -uo pipefail

# ── 0. Cron-safe environment ──────────────────────────────────────────────
export PATH="/usr/local/bin:/usr/local/sbin:/usr/bin:/bin:/snap/bin:${PATH:-}"
export HOME="${HOME:-$(eval echo ~"$(id -un)")}"
export GIT_TERMINAL_PROMPT=0   # never hang waiting for interactive credentials
export GIT_ASKPASS=""

PROJECT_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$PROJECT_PATH" || { echo "❌ Error: Could not enter project directory $PROJECT_PATH"; exit 1; }

# Create directory structure and set permissions right away
mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache

# Preserve machine-specific environment values before Git checkout
ENV_FILE="$PROJECT_PATH/.env"

if [ ! -f "$ENV_FILE" ]; then
    echo "❌ Error: .env file not found."
    exit 1
fi

get_env_value() {
    local key="$1"

    grep -E "^${key}=" "$ENV_FILE" \
        | tail -n1 \
        | cut -d '=' -f2-
}

RESTAURANT_ID=$(get_env_value "RESTAURANT_ID")
CLOUD_API_AUTH_KEY=$(get_env_value "CLOUD_API_AUTH_KEY")
CLOUD_URL=$(get_env_value "CLOUD_URL")

if [ -z "$RESTAURANT_ID" ] || [ -z "$CLOUD_API_AUTH_KEY" ]; then
    echo "❌ Error: RESTAURANT_ID and CLOUD_API_AUTH_KEY must exist in .env."
    exit 1
fi

CLOUD_URL="${CLOUD_URL:-https://tequilapos.net}"

chmod -R 777 storage bootstrap/cache 2>/dev/null || true
LOG_FILE="$PROJECT_PATH/storage/logs/auto-update.log"

log() {
    local msg="[$(date '+%Y-%m-%d %H:%M:%S')] $1"
    echo "$msg" | tee -a "$LOG_FILE"
}

LOCK_DIR="$PROJECT_PATH/storage/logs/.tequila-update.lock"

acquire_lock() {
    if mkdir "$LOCK_DIR" 2>/dev/null; then
        echo $$ > "$LOCK_DIR/pid"
        return 0
    fi
    local pid
    pid=$(cat "$LOCK_DIR/pid" 2>/dev/null || echo "")
    if [ -n "$pid" ] && ! kill -0 "$pid" 2>/dev/null; then
        log "⚠️  Found a stale lock left by dead process $pid. Removing it."
        rm -rf "$LOCK_DIR"
        if mkdir "$LOCK_DIR" 2>/dev/null; then
            echo $$ > "$LOCK_DIR/pid"
            return 0
        fi
    fi
    return 1
}

if ! acquire_lock; then
    log "⚠️  Another update process is already running. Exiting."
    exit 1
fi
trap 'rm -rf "$LOCK_DIR"' EXIT

log "=========================================================="
log "🌮  Tequila POS: Automated Background Update Started (Docker)"
log "=========================================================="

# ── 1. Fetch tags and check for update ───────────────────────────────────────
log "Fetching latest tags from remote repository..."

git config --global --add safe.directory "$PROJECT_PATH" || {
    log "❌ Error: Failed to set safe.directory for Git."
    exit 1
}

FETCH_OK=0
for attempt in 1 2 3; do
    FETCH_OUTPUT=$(git fetch --tags --force 2>&1)
    if [ $? -eq 0 ]; then
        FETCH_OK=1
        break
    fi
    log "⚠️  git fetch attempt $attempt/3 failed. Retrying in 5s..."
    sleep 5
done

if [ "$FETCH_OK" -ne 1 ]; then
    log "❌ Error: Failed to fetch tags from remote repository."
    log "Git output: $FETCH_OUTPUT"
    exit 1
fi

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

# ── 2. Check if Docker container configuration will change ────────────────────
DOCKER_CONFIG_CHANGED=0
if git diff --quiet HEAD "$LATEST_TAG" -- docker-compose.yml docker/ 2>/dev/null; then
    log "ℹ️  No Docker container configuration changes detected between $CURRENT_VERSION and $LATEST_TAG."
    DOCKER_CONFIG_CHANGED=0
else
    log "⚠️  Docker container configuration changes detected between releases."
    DOCKER_CONFIG_CHANGED=1
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

# Restore machine-specific values into docker-compose.yml
log "🔧 Restoring local Docker configuration..."

sedi() {
    sed -i.bak "$1" "$2" && rm -f "$2.bak"
}

sedi "s/RESTAURANT_ID: \"[^\"]*\"/RESTAURANT_ID: \"${RESTAURANT_ID}\"/" "$PROJECT_PATH/docker-compose.yml"
sedi "s/CLOUD_API_AUTH_KEY: \"[^\"]*\"/CLOUD_API_AUTH_KEY: \"${CLOUD_API_AUTH_KEY}\"/" "$PROJECT_PATH/docker-compose.yml"
sedi "s|CLOUD_URL: \"[^\"]*\"|CLOUD_URL: \"${CLOUD_URL}\"|" "$PROJECT_PATH/docker-compose.yml"

log "✅ Local Docker configuration restored."

if ! grep -Fq "RESTAURANT_ID: \"${RESTAURANT_ID}\"" "$PROJECT_PATH/docker-compose.yml"; then
    log "❌ Error: Failed to restore RESTAURANT_ID in docker-compose.yml."
    exit 1
fi

if ! grep -Fq "CLOUD_API_AUTH_KEY: \"${CLOUD_API_AUTH_KEY}\"" "$PROJECT_PATH/docker-compose.yml"; then
    log "❌ Error: Failed to restore CLOUD_API_AUTH_KEY in docker-compose.yml."
    exit 1
fi

if ! grep -Fq "CLOUD_URL: \"${CLOUD_URL}\"" "$PROJECT_PATH/docker-compose.yml"; then
    log "❌ Error: Failed to restore CLOUD_URL in docker-compose.yml."
    exit 1
fi

log "✅ Docker configuration values verified."

# ── 4. Verify Docker containers are running ───────────────────────────────────
if ! docker ps --format '{{.Names}}' 2>/dev/null | grep -qiE '(tequila-appserver|appserver)'; then
    log "⚠️  Docker containers are currently stopped. Starting Docker services..."
    docker compose up -d >> "$LOG_FILE" 2>&1 || {
        log "❌ Error: Failed to start Docker services."
        exit 1
    }
fi

# ── 5. Install PHP dependencies via Docker ────────────────────────────────────
log "📦 Installing PHP dependencies (composer install)..."
docker compose exec -T appserver composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --quiet >> "$LOG_FILE" 2>&1 || {
    log "❌ Error: Composer install failed."
    exit 1
}
log "✅ Composer dependencies installed."

# ── 6. Run Database Migrations ───────────────────────────────────────────────
log "🗄️  Running database migrations..."
docker compose exec -T appserver php artisan migrate --force --no-interaction >> "$LOG_FILE" 2>&1 || {
    log "❌ Error: Database migration failed."
    exit 1
}
log "✅ Database migrations completed."

# ── 7. Optimize Laravel Caches ───────────────────────────────────────────────
log "⚡ Clearing and rebuilding Laravel caches..."
docker compose exec -T appserver php artisan config:cache --no-interaction >> "$LOG_FILE" 2>&1 || true
docker compose exec -T appserver php artisan route:clear --no-interaction >> "$LOG_FILE" 2>&1 || true
docker compose exec -T appserver php artisan view:cache --no-interaction >> "$LOG_FILE" 2>&1 || true
docker compose exec -T appserver bash -c "chown -R www-data:www-data storage bootstrap/cache 2>/dev/null && chmod -R 777 storage bootstrap/cache 2>/dev/null" || true
chmod -R 777 storage bootstrap/cache 2>/dev/null || true
log "✅ Laravel caches rebuilt and permissions synced."

# ── 8. Zero/Near-Zero Downtime Graceful Reload ───────────────────────────────
if [ "$DOCKER_CONFIG_CHANGED" -eq 1 ]; then
    log "🔄 Performing full Docker build & restart to apply container configuration changes..."
    docker compose up -d --build --force-recreate >> "$LOG_FILE" 2>&1 || {
        log "❌ Error: Docker build/restart failed."
        exit 1
    }
    log "Reloading supervisor background workers..."
    docker compose exec -T appserver supervisorctl update >> "$LOG_FILE" 2>&1 || {
        log "❌ Error: Supervisor update failed."
        exit 1
    }
    docker compose exec -T appserver supervisorctl restart all >> "$LOG_FILE" 2>&1 || {
        log "❌ Error: Supervisor restart failed."
        exit 1
    }
    log "✅ Full Docker restart completed."
else
    log "✨ Option 1 Graceful Reload: Reloading queue workers & Apache with ZERO downtime..."
    docker compose exec -T appserver supervisorctl update >> "$LOG_FILE" 2>&1 || {
        log "❌ Error: Supervisor update failed."
        exit 1
    }
    docker compose exec -T appserver supervisorctl restart all >> "$LOG_FILE" 2>&1 || {
        log "❌ Error: Supervisor restart failed."
        exit 1
    }
    if docker compose exec -T appserver apachectl -k graceful >> "$LOG_FILE" 2>&1; then
        log "✅ Apache graceful reload completed."
    elif docker compose exec -T appserver service apache2 reload >> "$LOG_FILE" 2>&1; then
        log "✅ Apache reload completed."
    else
        log "❌ Error: Apache reload failed."
        exit 1
    fi
    log "✅ Graceful reload completed seamlessly!"
fi

log "🎉 =========================================================="
log "✅  TEQUILA POS SUCCESSFULLY UPDATED TO VERSION: $LATEST_TAG"
log "🎉 =========================================================="
echo ""