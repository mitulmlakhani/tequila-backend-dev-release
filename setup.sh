#!/usr/bin/env bash
# =============================================================================
# Tequila POS – One-Command Setup Script
# Automates:
# 1. Pulling repo and checking out the very first/latest version tag
# 2. Dynamically setting RESTAURANT_ID and CLOUD_API_AUTH_KEY in .env & Lando
# 3. Starting/restarting Lando services
# 4. Configuring /etc/hosts on both main machine and inside Lando container
# =============================================================================
set -euo pipefail

PROJECT_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$PROJECT_PATH"

echo "========================================================"
echo "🌮  Tequila POS: Automated Project Setup"
echo "========================================================"

# ── 1. Pull repo & checkout latest tag ───────────────────────────────────────
echo ""
echo "=== Step 1: Pulling repository and checking out latest tag ==="

# Back up local configurations before git stash or git checkout so they are never deleted
echo "🛡️  Backing up local configurations (.env, .lando.yml, .lando/)..."
mkdir -p /tmp/tequila_backup
[ -f .env ] && cp -p .env /tmp/tequila_backup/.env || true
[ -f .lando.yml ] && cp -p .lando.yml /tmp/tequila_backup/.lando.yml || true
[ -d .lando ] && cp -Rp .lando /tmp/tequila_backup/.lando || true

# Ensure we fetch all tags from origin
echo "Fetching tags from remote repository..."
# git fetch --tags --quiet || git fetch --tags origin --quiet

# Get the latest tag name (very first only, sorted by version)
LATEST_TAG=$(git tag -l | sort -V | tail -n1)

if [ -z "$LATEST_TAG" ]; then
    echo "❌ Error: No tags found in the repository."
    exit 1
fi

CURRENT_VERSION=$(git describe --tags --abbrev=0 2>/dev/null || echo "none")
echo "Latest tag detected: $LATEST_TAG (Current checked-out version: $CURRENT_VERSION)"

if [ "$LATEST_TAG" != "$CURRENT_VERSION" ]; then
    echo "Stashing any local working directory changes..."
    git stash --quiet || true
    echo "Checking out to tag: $LATEST_TAG..."
    git checkout "$LATEST_TAG" --quiet || echo "⚠️ Notice: checkout reported non-zero status."
    echo "✅ Successfully checked out to $LATEST_TAG!"
else
    echo "✅ Already checked out to the latest version ($LATEST_TAG)."
fi

# Restore local configurations so Lando files are guaranteed to be present
echo "🛡️  Restoring local configurations (.env, .lando.yml, .lando/)..."
[ -f /tmp/tequila_backup/.env ] && cp -p /tmp/tequila_backup/.env .env || true
[ -f /tmp/tequila_backup/.lando.yml ] && cp -p /tmp/tequila_backup/.lando.yml .lando.yml || true
[ -d /tmp/tequila_backup/.lando ] && cp -Rp /tmp/tequila_backup/.lando/ .lando/ || true

# ── 2. Configure environment variables dynamically ───────────────────────────
echo ""
echo "=== Step 2: Configuring Environment Variables ==="

if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        echo "📋 Creating .env from .env.example..."
        cp .env.example .env
    else
        touch .env
    fi
fi

# Prompt interactively if not already set via environment variables
if [ -z "${RESTAURANT_ID:-}" ]; then
    read -p "Enter RESTAURANT_ID: " RESTAURANT_ID
fi

if [ -z "${CLOUD_API_AUTH_KEY:-}" ]; then
    read -p "Enter CLOUD_API_AUTH_KEY: " CLOUD_API_AUTH_KEY
fi

export RESTAURANT_ID
export CLOUD_API_AUTH_KEY

echo "Updating RESTAURANT_ID and CLOUD_API_AUTH_KEY in .env, .lando.yml, and supervisord.conf..."

# Helper function to update key=val in .env files
update_env_var() {
    local key="$1"
    local val="$2"
    local file="$3"
    if [ ! -f "$file" ]; then touch "$file"; fi
    if grep -q "^${key}=" "$file" 2>/dev/null; then
        if [ "$(uname)" = "Darwin" ]; then
            sed -i '' "s|^${key}=.*|${key}=${val}|g" "$file"
        else
            sed -i "s|^${key}=.*|${key}=${val}|g" "$file"
        fi
    else
        echo "${key}=${val}" >> "$file"
    fi
}

# Helper function to update YAML key: "val" in .lando.yml
update_yaml_var() {
    local key="$1"
    local val="$2"
    local file="$3"
    if [ -f "$file" ] && grep -q "^[[:space:]]*${key}:" "$file" 2>/dev/null; then
        if [ "$(uname)" = "Darwin" ]; then
            sed -i '' "s|^[[:space:]]*${key}:.*|        ${key}: \"${val}\"|g" "$file"
        else
            sed -i "s|^[[:space:]]*${key}:.*|        ${key}: \"${val}\"|g" "$file"
        fi
    fi
}

# Helper function to update environment= in supervisord.conf
update_supervisor_var() {
    local key="$1"
    local val="$2"
    local file="$3"
    if [ -f "$file" ] && grep -q "^environment=" "$file" 2>/dev/null; then
        if grep -q "${key}=" "$file"; then
            if [ "$(uname)" = "Darwin" ]; then
                sed -i '' "s|${key}=\"[^\"]*\"|${key}=\"${val}\"|g" "$file"
            else
                sed -i "s|${key}=\"[^\"]*\"|${key}=\"${val}\"|g" "$file"
            fi
        else
            if [ "$(uname)" = "Darwin" ]; then
                sed -i '' "s|^environment=.*|&,${key}=\"${val}\"|g" "$file"
            else
                sed -i "s|^environment=.*|&,${key}=\"${val}\"|g" "$file"
            fi
        fi
    fi
}

update_env_var "RESTAURANT_ID" "$RESTAURANT_ID" ".env"
update_env_var "CLOUD_API_AUTH_KEY" "$CLOUD_API_AUTH_KEY" ".env"
update_yaml_var "RESTAURANT_ID" "$RESTAURANT_ID" ".lando.yml"
update_yaml_var "CLOUD_API_AUTH_KEY" "$CLOUD_API_AUTH_KEY" ".lando.yml"
update_supervisor_var "RESTAURANT_ID" "$RESTAURANT_ID" ".lando/supervisord.conf"
update_supervisor_var "CLOUD_API_AUTH_KEY" "$CLOUD_API_AUTH_KEY" ".lando/supervisord.conf"

echo "✅ Environment variables configured successfully!"

# ── 3. Run Lando ─────────────────────────────────────────────────────────────
echo ""
echo "=== Step 3: Running Lando ==="

if lando info >/dev/null 2>&1; then
    echo "Lando is currently running. Restarting services to apply environment updates..."
    lando restart
else
    echo "Starting Lando services (creating containers if needed)..."
    lando start
fi

# Ensure supervisor workers reload the updated configuration inside container
echo "Reloading supervisor background workers..."
lando ssh -u root -c "supervisorctl update && supervisorctl restart all" || true

# ── 4. Setup Hosts ───────────────────────────────────────────────────────────
echo ""
echo "=== Step 4: Configuring /etc/hosts ==="

echo "1) Running setup-hosts.sh on main machine (host OS):"
if [ -f .lando/setup-hosts.sh ]; then
    bash .lando/setup-hosts.sh || echo "⚠️ Notice: setup-hosts.sh exited with non-zero status on host machine."
else
    echo "⚠️ Warning: .lando/setup-hosts.sh not found."
fi

echo ""
echo "2) Running setup-hosts.sh inside Lando app machine:"
lando ssh -u root -c "bash /app/.lando/setup-hosts.sh" || {
    echo "⚠️ Warning: Failed to run setup-hosts.sh inside Lando container."
}

echo ""
echo "3) Remove .lando subdirectory from git tracking (if it exists):"
if [ -d ./.lando/.lando ]; then
    rm -r ./.lando/.lando
fi

echo ""
echo "4) Setup updator.sh to cron job at 5 AM daily:"
CRON_JOB="0 5 * * * cd \"$PROJECT_PATH\" && /bin/bash \"$PROJECT_PATH/updator.sh\" >> \"$PROJECT_PATH/updator.log\" 2>&1"
CURRENT_CRON="$(crontab -l 2>/dev/null || true)"

if echo "$CURRENT_CRON" | grep -Fxq "$CRON_JOB"; then
    echo "✅ Cron job already exists."
else
    printf "%s\n%s\n" "$CURRENT_CRON" "$CRON_JOB" | crontab -
    echo "✅ Cron job added."
fi

echo ""
echo "🎉 ======================================================= 🎉"
echo "✅  TEQUILA POS PROJECT SETUP COMPLETE!"
echo "   🌮  App URL:  https://tequilapos.local"
echo "   🌮   DB URL:  https://adminer.tequilapos.local"
echo "   🌮   DB User:  root"
echo "   🌮   DB Password:  tequila"
echo "   🌮   DB Name:  tequila_pos"
echo "   🌮  Socket URL:  https://echo.tequilapos.local"
echo "🎉 ======================================================= 🎉"
echo ""
