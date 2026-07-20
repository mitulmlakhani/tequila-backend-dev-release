#!/usr/bin/env bash
# ============================================================================
# Printer Management System — Production Installer
# ----------------------------------------------------------------------------
# This script installs, configures, and starts the Printer Management stack
# via Docker Compose. Each step is documented and verified before moving on.
#
# Usage:
#   ./install-printer-management.sh
#
# Requirements:
#   - Docker + Docker Compose plugin installed
#   - git installed (if cloning from a repo)
#   - Run as a user with sudo privileges (or write permissions to APP_DIR)
# ============================================================================
set -euo pipefail

# ----------------------------------------------------------------------------
# Configuration (override via environment variables before running if needed)
# ----------------------------------------------------------------------------
APP_DIR="${APP_DIR:-$HOME/printer-management}"
REPO_URL="${REPO_URL:-https://github.com/TequilaPOS/TequilaPOS-Printer-Management.git}"
DRIVER_SET="${DRIVER_SET:-lite}"          # lite | common | full
TZ_VALUE="${TZ_VALUE:-America/Chicago}"
BACKEND_PORT="${BACKEND_PORT:-3000}"

# Host ports — changed from the defaults (80, 6001, 3306) because those are
# already in use by other apps on this server.
FRONTEND_PORT="${FRONTEND_PORT:-8080}"
WEBSOCKET_PORT="${WEBSOCKET_PORT:-6002}"
MYSQL_PORT="${MYSQL_PORT:-3306}"          # Port 3306 of shared tequila-database
MYSQL_HOST="${MYSQL_HOST:-host.docker.internal}"
MYSQL_CONTAINER="${MYSQL_CONTAINER:-tequila-database}"
MYSQL_DATABASE="${MYSQL_DATABASE:-printer_management}"
MYSQL_USER="${MYSQL_USER:-printer_admin}"
FRONTEND_URL="${FRONTEND_URL:-}"
HEALTH_RETRIES=15
HEALTH_DELAY=4

# ----------------------------------------------------------------------------
# Helpers
# ----------------------------------------------------------------------------
c_green="\033[1;32m"; c_yellow="\033[1;33m"; c_red="\033[1;31m"; c_reset="\033[0m"
step()  { echo -e "\n${c_yellow}==>${c_reset} $*"; }
ok()    { echo -e "${c_green}✔${c_reset} $*"; }
fail()  { echo -e "${c_red}✘ $*${c_reset}" >&2; exit 1; }

require_cmd() {
  command -v "$1" >/dev/null 2>&1 || fail "Required command '$1' not found. Please install it and re-run."
}

# ============================================================================
# Step 0 — Prerequisite checks
# ============================================================================
step "Step 0/9: Checking prerequisites"
require_cmd docker
require_cmd sudo
require_cmd openssl
docker compose version >/dev/null 2>&1 || fail "Docker Compose plugin not found (need 'docker compose')."
ok "Docker, Docker Compose, and openssl are available"

# ============================================================================
# Step 1 — Create application directory (and set ownership immediately, so
# the current user — not root — owns it before anything writes into it)
# ============================================================================
step "Step 1/9: Creating application directory at ${APP_DIR}"
mkdir -p "${APP_DIR}" 2>/dev/null || sudo mkdir -p "${APP_DIR}"
if [ ! -w "${APP_DIR}" ]; then
  sudo chown -R "$USER":"$(id -gn)" "${APP_DIR}"
fi
cd "${APP_DIR}"
ok "Directory ready and owned by ${USER}: ${APP_DIR}"

# ============================================================================
# Step 2 — Fetch project files (either by cloning or updating if .git exists)
# ============================================================================
step "Step 2/9: Fetching project files"
if [ -d ".git" ]; then
  ok "Repository already present, pulling latest changes"
  git checkout -- nginx.conf docker-compose.yml docker-compose.yaml 2>/dev/null || true
  git pull --ff-only
elif [ -z "$(ls -A "${APP_DIR}" 2>/dev/null)" ]; then
  require_cmd git
  git clone "${REPO_URL}" .
  ok "Cloned repository from ${REPO_URL}"
else
  ok "Directory not empty and not a git repo — assuming files were copied manually (Option B). Skipping clone."
fi

# ============================================================================
# Step 3 — Re-assert ownership (covers files that git/scp may have written
# with different ownership, e.g. if run under sudo or as a different user)
# ============================================================================
step "Step 3/9: Confirming file ownership"
if [ ! -w "${APP_DIR}" ]; then
  sudo chown -R "$USER":"$(id -gn)" "${APP_DIR}"
fi
ok "Ownership set to ${USER}"

# ============================================================================
# Step 4 — Generate secrets dynamically and load defaults
# ============================================================================
step "Step 4/9: Generating secure secrets and loading defaults"
if [ -f .env ]; then
  ok ".env already exists — backing it up to .env.bak.$(date +%s) and keeping existing secrets"
  cp .env ".env.bak.$(date +%s)"
  # Reuse existing secrets/passwords if already generated, so re-running the
  # script doesn't invalidate existing sessions or DB credentials.
  JWT_SECRET="$(grep -m1 '^JWT_SECRET=' .env | cut -d= -f2-)"
  JWT_REFRESH_SECRET="$(grep -m1 '^JWT_REFRESH_SECRET=' .env | cut -d= -f2-)"
  MYSQL_ROOT_PASSWORD="$(grep -m1 '^MYSQL_ROOT_PASSWORD=' .env | cut -d= -f2-)"
  MYSQL_PASSWORD="$(grep -m1 '^MYSQL_PASSWORD=' .env | cut -d= -f2-)"
  MYSQL_HOST="$(grep -m1 '^DB_HOST=' .env | cut -d= -f2- || grep -m1 '^MYSQL_HOST=' .env | cut -d= -f2- || echo "")"
  MYSQL_CONTAINER="$(grep -m1 '^MYSQL_CONTAINER=' .env | cut -d= -f2- || echo "")"
  MYSQL_DATABASE="$(grep -m1 '^MYSQL_DATABASE=' .env | cut -d= -f2- || grep -m1 '^DB_NAME=' .env | cut -d= -f2- || echo "")"
  MYSQL_USER="$(grep -m1 '^MYSQL_USER=' .env | cut -d= -f2- || grep -m1 '^DB_USER=' .env | cut -d= -f2- || echo "")"
  FRONTEND_URL="$(grep -m1 '^FRONTEND_URL=' .env | cut -d= -f2- || echo "")"
fi

# Generate a fresh, cryptographically strong JWT secret if none was carried over
JWT_SECRET="${JWT_SECRET:-$(openssl rand -hex 64)}"
JWT_REFRESH_SECRET="${JWT_REFRESH_SECRET:-$(openssl rand -hex 64)}"
# Default passwords and shared database target (defaults match tequila-database)
MYSQL_ROOT_PASSWORD="${MYSQL_ROOT_PASSWORD:-tequila}"
MYSQL_PASSWORD="${MYSQL_PASSWORD:-printer_admin}"
MYSQL_HOST="${MYSQL_HOST:-host.docker.internal}"
MYSQL_CONTAINER="${MYSQL_CONTAINER:-tequila-database}"
MYSQL_DATABASE="${MYSQL_DATABASE:-printer_management}"
MYSQL_USER="${MYSQL_USER:-printer_admin}"
FRONTEND_URL="${FRONTEND_URL:-}"

ok "JWT secrets and database configuration resolved"

# ============================================================================
# Step 5 — Write production .env file
# ============================================================================
step "Step 5/9: Writing .env file"
cat > .env << EOF
# ===========================================
# Production Environment Configuration
# Generated: $(date -u +"%Y-%m-%dT%H:%M:%SZ")
# ===========================================

# MySQL Database (shared with tequila-database)
MYSQL_CONTAINER=${MYSQL_CONTAINER}
MYSQL_HOST=${MYSQL_HOST}
MYSQL_PORT=${MYSQL_PORT}
MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}
MYSQL_DATABASE=${MYSQL_DATABASE}
MYSQL_USER=${MYSQL_USER}
MYSQL_PASSWORD=${MYSQL_PASSWORD}

# Backend DB Connection
DB_HOST=${MYSQL_HOST}
DB_PORT=${MYSQL_PORT}
DB_NAME=${MYSQL_DATABASE}
DB_USER=${MYSQL_USER}
DB_PASSWORD=${MYSQL_PASSWORD}

# Backend
NODE_ENV=production
JWT_SECRET=${JWT_SECRET}
JWT_REFRESH_SECRET=${JWT_REFRESH_SECRET}
JWT_EXPIRES_IN=24h
PORT=${BACKEND_PORT}

# Backend Driver Pack:
# - lite (default, ~500MB): HP, Kyocera, Epson, Brother (IPP Everywhere)
# - common (~1.5GB): + Canon, Ricoh, Lexmark, Samsung drivers
# - full (~3GB): All CUPS drivers for legacy printers
DRIVER_SET=${DRIVER_SET}

# Frontend
VITE_API_URL=/api
FRONTEND_URL=${FRONTEND_URL}
FRONTEND_PORT=${FRONTEND_PORT}
WEBSOCKET_PORT=${WEBSOCKET_PORT}
MYSQL_PORT=${MYSQL_PORT}

# Timezone
TZ=${TZ_VALUE}
EOF
chmod 600 .env
ok "Wrote ${APP_DIR}/.env (chmod 600)"

# ============================================================================
# Step 5b — Sanity-check docker-compose.yml port mappings (read-only check)
# ============================================================================
step "Step 5b/9: Checking docker-compose.yml for port conflicts"
if [ -f docker-compose.yml ] || [ -f docker-compose.yaml ]; then
  COMPOSE_FILE=$( [ -f docker-compose.yml ] && echo docker-compose.yml || echo docker-compose.yaml )
  for p in 80 6001; do
    if grep -Eq "\"?${p}:[0-9]+\"?" "${COMPOSE_FILE}"; then
      echo -e "${c_yellow}Warning:${c_reset} ${COMPOSE_FILE} still hard-codes host port ${p}, which is already in use."
      echo "         Update its port mapping to use \${FRONTEND_PORT} or \${WEBSOCKET_PORT}"
      echo "         (e.g. \"\${FRONTEND_PORT}:80\") so it picks up the values from .env."
    fi
  done
else
  echo -e "${c_yellow}Note:${c_reset} no docker-compose.yml found yet in ${APP_DIR} — skipping port conflict check."
  echo "      Once it's in place, make sure it maps ports via \${FRONTEND_PORT}, \${WEBSOCKET_PORT}"
  echo "      rather than hard-coded 80 / 6001."
fi

# ============================================================================
# Step 5c — Sanity-check nginx.conf proxy_pass target
# ============================================================================
step "Step 5c/9: Checking nginx.conf proxy configuration"
if [ -f nginx.conf ]; then
  if grep -q "proxy_pass http://backend:3000/api;" nginx.conf; then
    echo -e "${c_yellow}Note:${c_reset} nginx.conf contains proxy_pass ending with /api; (\`proxy_pass http://backend:3000/api;\`)."
    echo "      Patching to proxy_pass http://backend:3000; so requests preserve full path."
    cp nginx.conf "nginx.conf.bak.$(date +%s)"
    sed -i.bak 's|proxy_pass http://backend:3000/api;|proxy_pass http://backend:3000;|g' nginx.conf && rm -f nginx.conf.bak
    ok "Patched nginx.conf proxy_pass"
  else
    ok "nginx.conf proxy_pass check OK"
  fi
fi

# ============================================================================
# Step 5d — Sanity-check docker-compose.yml database configuration
# ============================================================================
step "Step 5d/9: Checking docker-compose.yml database configuration"
if [ -f docker-compose.yml ] || [ -f docker-compose.yaml ]; then
  COMPOSE_FILE=$( [ -f docker-compose.yml ] && echo docker-compose.yml || echo docker-compose.yaml )
  if grep -Eq '^[[:space:]]*DB_HOST:[[:space:]]*"?mysql"?' "${COMPOSE_FILE}"; then
    echo -e "${c_yellow}Note:${c_reset} ${COMPOSE_FILE} hardcodes DB_HOST: mysql."
    echo "      Patching DB_HOST and DB_PORT to use environment variables from .env."
    cp "${COMPOSE_FILE}" "${COMPOSE_FILE}.bak.$(date +%s)"
    sed -i.bak 's|DB_HOST:[[:space:]]*mysql|DB_HOST: ${DB_HOST:-mysql}|g; s|DB_HOST:[[:space:]]*"mysql"|DB_HOST: ${DB_HOST:-mysql}|g' "${COMPOSE_FILE}"
    sed -i.bak 's|DB_PORT:[[:space:]]*3306|DB_PORT: ${DB_PORT:-3306}|g; s|DB_PORT:[[:space:]]*"3306"|DB_PORT: ${DB_PORT:-3306}|g' "${COMPOSE_FILE}"
    rm -f "${COMPOSE_FILE}.bak"
    ok "Patched ${COMPOSE_FILE} database configuration"
  else
    ok "${COMPOSE_FILE} database configuration check OK"
  fi
fi

# ============================================================================
# Step 6 — Build images
# ============================================================================
step "Step 6/9: Building Docker images (backend and frontend)"
docker compose build backend frontend
ok "Images built successfully"

# ============================================================================
# Step 7 — Start services (excluding local mysql)
# ============================================================================
step "Step 7/9: Starting services (detached mode without local mysql service)"
# Stop and remove any local mysql container if present in compose or Docker so it does not conflict
docker compose stop mysql 2>/dev/null || true
docker compose rm -s -v -f mysql 2>/dev/null || true
docker rm -f printer-mysql 2>/dev/null || true

# Explicitly start ONLY backend, frontend, and nginx with --no-deps so printer-mysql is never started via depends_on
docker compose up -d --no-deps --remove-orphans backend frontend nginx
ok "Services started (backend, frontend, nginx) — local mysql service disabled and cleaned up"

# ============================================================================
# Step 7a — Reload nginx so the /api proxy_pass fix from Step 5c takes
# effect (if the container was already running with old config)
# ============================================================================
step "Step 7a/9: Reloading nginx config"
if docker compose exec -T nginx nginx -s reload 2>/dev/null; then
  ok "nginx config reloaded"
else
  echo "Note: nginx -s reload failed — recreating the container instead"
  docker compose up -d --no-deps --force-recreate nginx
  ok "nginx container recreated"
fi

# ============================================================================
# Step 7b — Import init.sql into MySQL (or shared instance)
# ============================================================================
step "Step 7b/9: Importing init.sql into MySQL (${MYSQL_CONTAINER:-tequila-database})"

INIT_SQL_FILE="${INIT_SQL_FILE:-init.sql}"
MYSQL_SERVICE="${MYSQL_SERVICE:-}"
MYSQL_CMD=""
MYSQL_PING_CMD=""

if [ ! -f "${INIT_SQL_FILE}" ]; then
  echo -e "${c_yellow}Note:${c_reset} ${INIT_SQL_FILE} not found in ${APP_DIR} — skipping import."
else
  # Detect whether target MySQL is running as an external/shared container (e.g. tequila-database)
  # or as a service inside docker-compose.yml
  if [ -n "${MYSQL_CONTAINER:-}" ] && (docker inspect "${MYSQL_CONTAINER}" >/dev/null 2>&1 || docker ps -a --format '{{.Names}}' | grep -qx "${MYSQL_CONTAINER}"); then
    ok "Using standalone MySQL container: ${MYSQL_CONTAINER}"
    MYSQL_CMD="docker exec -i ${MYSQL_CONTAINER} mysql"
    MYSQL_PING_CMD="docker exec -i ${MYSQL_CONTAINER} mysqladmin ping"
  else
    # Fall back to checking docker-compose.yml for a local MySQL service
    if [ -z "${MYSQL_SERVICE}" ]; then
      for candidate in mysql db database mariadb; do
        if docker compose config --services 2>/dev/null | grep -qx "${candidate}"; then
          MYSQL_SERVICE="${candidate}"
          break
        fi
      done
    fi

    if [ -n "${MYSQL_SERVICE}" ]; then
      ok "Using local compose MySQL service: ${MYSQL_SERVICE}"
      MYSQL_CMD="docker compose exec -T ${MYSQL_SERVICE} mysql"
      MYSQL_PING_CMD="docker compose exec -T ${MYSQL_SERVICE} mysqladmin ping"
    elif command -v mysql >/dev/null 2>&1 && command -v mysqladmin >/dev/null 2>&1; then
      ok "Using host mysql CLI connecting to ${MYSQL_HOST}:${MYSQL_PORT}"
      MYSQL_CMD="mysql -h ${MYSQL_HOST} -P ${MYSQL_PORT}"
      MYSQL_PING_CMD="mysqladmin ping -h ${MYSQL_HOST} -P ${MYSQL_PORT}"
    else
      echo -e "${c_yellow}Warning:${c_reset} could not detect running container '${MYSQL_CONTAINER:-}' or local compose service."
      echo "         Set MYSQL_CONTAINER=<name> or MYSQL_SERVICE=<name> and re-run."
    fi
  fi

  if [ -n "${MYSQL_PING_CMD:-}" ]; then
    # Wait for MySQL to actually accept connections before importing
    mysql_attempt=1
    mysql_ready=false
    while [ "${mysql_attempt}" -le "${HEALTH_RETRIES}" ]; do
      if ${MYSQL_PING_CMD} -uroot -p"${MYSQL_ROOT_PASSWORD}" --silent >/dev/null 2>&1; then
        mysql_ready=true
        break
      fi
      echo "   ...waiting for MySQL (${MYSQL_CONTAINER:-$MYSQL_SERVICE}) to accept connections (attempt ${mysql_attempt}/${HEALTH_RETRIES})"
      sleep "${HEALTH_DELAY}"
      mysql_attempt=$((mysql_attempt + 1))
    done

    if [ "${mysql_ready}" != true ]; then
      echo -e "${c_red}MySQL did not become ready in time — skipping init.sql import.${c_reset}"
      echo "         You can import it manually later with:"
      echo "         ${MYSQL_CMD} -uroot -p'${MYSQL_ROOT_PASSWORD}' printer_management < ${INIT_SQL_FILE}"
    else
      # Ensure database and user exist on the shared MySQL instance before importing
      if ${MYSQL_CMD} -uroot -p"${MYSQL_ROOT_PASSWORD}" -e \
           "CREATE DATABASE IF NOT EXISTS \`${MYSQL_DATABASE}\`;
            CREATE USER IF NOT EXISTS '${MYSQL_USER}'@'%' IDENTIFIED WITH mysql_native_password BY '${MYSQL_PASSWORD}';
            ALTER USER '${MYSQL_USER}'@'%' IDENTIFIED WITH mysql_native_password BY '${MYSQL_PASSWORD}';
            GRANT ALL PRIVILEGES ON \`${MYSQL_DATABASE}\`.* TO '${MYSQL_USER}'@'%';
            FLUSH PRIVILEGES;" 2>/dev/null; then
        ok "Database '${MYSQL_DATABASE}' and user '${MYSQL_USER}' configured on MySQL instance"
      else
        echo -e "${c_yellow}Warning:${c_reset} Could not verify/create database or user via root credentials. Ensure MYSQL_ROOT_PASSWORD matches target MySQL instance."
      fi

      # Check whether the database already has tables — if so, assume it was
      # already imported (or is live) and skip, rather than re-running init.sql
      TABLE_COUNT=$(${MYSQL_CMD} -uroot -p"${MYSQL_ROOT_PASSWORD}" -N -e \
        "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='${MYSQL_DATABASE}';" 2>/dev/null | tr -d '\r')

      if [ "${TABLE_COUNT:-0}" -gt 0 ] 2>/dev/null; then
        ok "Database '${MYSQL_DATABASE}' already has ${TABLE_COUNT} table(s) — skipping init.sql import (already initialized)."
      else
        if ${MYSQL_CMD} -uroot -p"${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" < "${INIT_SQL_FILE}"; then
          ok "init.sql imported successfully into '${MYSQL_DATABASE}'"
        else
          fail "Failed to import ${INIT_SQL_FILE} into MySQL. Check the error output above."
        fi
      fi
    fi
  fi
fi

# ============================================================================
# Step 7c — Fix default admin password hash
# ============================================================================
step "Step 7c/9: Fixing default admin password hash"
 
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@printer.local}"
# Verified hash for "Admin123!" — generated and confirmed with bcrypt.checkpw()
ADMIN_PASSWORD_HASH="${ADMIN_PASSWORD_HASH:-\$2b\$10\$qIH8KWtAnC8qrVu02dgK0.r/OvEOpJ2R7ZaHr7bVFMHn11P67i/E2}"

if [ "${mysql_ready:-false}" = true ] && [ -n "${MYSQL_CMD:-}" ]; then
  if ${MYSQL_CMD} -uroot -p"${MYSQL_ROOT_PASSWORD}" "${MYSQL_DATABASE}" -e \
       "UPDATE users SET password='${ADMIN_PASSWORD_HASH}' WHERE email='${ADMIN_EMAIL}';" 2>/dev/null; then
    ok "Admin password hash fixed for ${ADMIN_EMAIL}"
    if [ "${ADMIN_PASSWORD_HASH}" = '$2b$10$qIH8KWtAnC8qrVu02dgK0.r/OvEOpJ2R7ZaHr7bVFMHn11P67i/E2' ]; then
      ok "Login with: ${ADMIN_EMAIL} / Admin123!"
    fi
  else
    echo -e "${c_yellow}Note:${c_reset} could not update admin password (table/user may not exist yet, or MySQL wasn't reachable). Skipping."
  fi
else
  echo -e "${c_yellow}Note:${c_reset} MySQL instance not ready — skipping admin password fix."
fi

if [ "${mysql_ready:-false}" = true ]; then
  # Restart backend and nginx so they pick up the initialized database and users cleanly
  echo "   ...restarting backend and nginx services to pick up database initialization"
  docker compose restart backend nginx >/dev/null 2>&1 || true
  # Final safety check: ensure local mysql did not start as a side-effect of any compose operation
  docker compose stop mysql >/dev/null 2>&1 || true
  docker rm -f printer-mysql >/dev/null 2>&1 || true
fi

# ============================================================================
# Step 8 — Verify containers are running
# ============================================================================
step "Step 8/9: Verifying container status"
sleep 3
docker compose ps
if docker compose ps --status=exited --services 2>/dev/null | grep -q .; then
  echo -e "${c_red}One or more containers exited unexpectedly. Recent logs:${c_reset}"
  docker compose logs --tail=50
  fail "Container(s) not running. See logs above."
fi
ok "All containers reported as up"

# ============================================================================
# Step 9 — Health checks (with retries, since services may still be booting)
# ============================================================================
step "Step 9/9: Verifying services are actually responding"

wait_for() {
  local desc="$1" url_or_cmd="$2" attempt=1
  while [ "$attempt" -le "$HEALTH_RETRIES" ]; do
    if eval "$url_or_cmd" >/dev/null 2>&1; then
      ok "$desc is responding (attempt ${attempt}/${HEALTH_RETRIES})"
      return 0
    fi
    echo "   ...waiting for ${desc} (attempt ${attempt}/${HEALTH_RETRIES})"
    sleep "$HEALTH_DELAY"
    attempt=$((attempt + 1))
  done
  echo -e "${c_red}${desc} did not become ready in time.${c_reset}"
  docker compose logs --tail=50
  fail "${desc} health check failed."
}

wait_for "Backend API (health endpoint)" \
  "curl -fsS http://localhost:${BACKEND_PORT}/api/system/health"

wait_for "Frontend (HTTP)" \
  "curl -fsS -o /dev/null http://localhost:${FRONTEND_PORT}"

step "Checking CUPS print subsystem"
if docker exec printer-backend lpstat -r 2>/dev/null | grep -qi "is running"; then
  ok "CUPS scheduler is running"
else
  echo -e "${c_yellow}Warning:${c_reset} could not confirm CUPS scheduler status. Raw output:"
  docker exec printer-backend lpstat -r || true
fi

# ============================================================================
# Done
# ============================================================================
echo -e "\n${c_green}=====================================================${c_reset}"
echo -e "${c_green} Installation complete!${c_reset}"
echo -e "${c_green}=====================================================${c_reset}"
echo "  Frontend:      http://localhost:${FRONTEND_PORT}"
echo "  Backend API:   http://localhost:${BACKEND_PORT}/api"
echo "  WebSocket:     localhost:${WEBSOCKET_PORT} (was 6001)"
echo "  MySQL:         shared instance (${MYSQL_CONTAINER:-tequila-database}) on port ${MYSQL_PORT}"
echo "  App directory: ${APP_DIR}"
echo "  Secrets file:  ${APP_DIR}/.env (chmod 600 — keep this safe)"
echo "  Database init: init.sql imported (or already present — see Step 7b above)"
echo
echo "  Tip: re-running this script will reuse existing secrets in .env"
echo "  instead of generating new ones, so it's safe to re-run for updates."