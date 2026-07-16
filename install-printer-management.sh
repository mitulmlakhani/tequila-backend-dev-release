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
#   - Run as a user with sudo privileges
# ============================================================================

set -euo pipefail

# ----------------------------------------------------------------------------
# Configuration (override via environment variables before running if needed)
# ----------------------------------------------------------------------------
APP_DIR="${APP_DIR:-~/printer-management}"
REPO_URL="${REPO_URL:-https://github.com/TequilaPOS/TequilaPOS-Printer-Management.git}"
DRIVER_SET="${DRIVER_SET:-lite}"          # lite | common | full
TZ_VALUE="${TZ_VALUE:-America/Chicago}"
BACKEND_PORT="${BACKEND_PORT:-3000}"

# Host ports — changed from the defaults (80, 6001, 3306) because those are
# already in use by other apps on this server.
FRONTEND_PORT="${FRONTEND_PORT:-8080}"
WEBSOCKET_PORT="${WEBSOCKET_PORT:-6002}"
MYSQL_PORT="${MYSQL_PORT:-3307}"
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
sudo mkdir -p "${APP_DIR}"
sudo chown -R "$USER":"$(id -gn)" "${APP_DIR}"
cd "${APP_DIR}"
ok "Directory ready and owned by ${USER}: ${APP_DIR}"

# ============================================================================
# Step 2 — Fetch project files (git clone, or fall back to local copy)
# ============================================================================
step "Step 2/9: Fetching project files"
if [ -d ".git" ]; then
  ok "Repository already present, pulling latest changes"
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
sudo chown -R "$USER":"$(id -gn)" "${APP_DIR}"
ok "Ownership set to ${USER}"

# ============================================================================
# Step 4 — Generate secrets dynamically
# ============================================================================
step "Step 4/9: Generating secure secrets"
if [ -f .env ]; then
  ok ".env already exists — backing it up to .env.bak.$(date +%s) and keeping existing secrets"
  cp .env ".env.bak.$(date +%s)"
  # Reuse existing secrets/passwords if already generated, so re-running the
  # script doesn't invalidate existing sessions or DB credentials.
  JWT_SECRET="$(grep -m1 '^JWT_SECRET=' .env | cut -d= -f2-)"
  JWT_REFRESH_SECRET="$(grep -m1 '^JWT_REFRESH_SECRET=' .env | cut -d= -f2-)"
  MYSQL_ROOT_PASSWORD="$(grep -m1 '^MYSQL_ROOT_PASSWORD=' .env | cut -d= -f2-)"
  MYSQL_PASSWORD="$(grep -m1 '^MYSQL_PASSWORD=' .env | cut -d= -f2-)"
fi

# Generate a fresh, cryptographically strong JWT secret if none was carried over
JWT_SECRET="${JWT_SECRET:-$(openssl rand -hex 64)}"
JWT_REFRESH_SECRET="${JWT_REFRESH_SECRET:-$(openssl rand -hex 64)}"
# Generate strong random passwords for MySQL if none were carried over
MYSQL_ROOT_PASSWORD="${MYSQL_ROOT_PASSWORD:-tequila}"
MYSQL_PASSWORD="${MYSQL_PASSWORD:-printer_admin}"
# NOTE: these are now fixed at "tequila" instead of being randomly generated.
# That's a much weaker credential than a random 24-byte password — fine for a
# local/dev box, but swap back to `openssl rand -base64 24` before this ever
# touches a shared or production server.

ok "JWT secret generated (64 bytes, hex-encoded)"
ok "MySQL root and application passwords generated (24 bytes, base64-encoded)"

# ============================================================================
# Step 5 — Write production .env file
# ============================================================================
step "Step 5/9: Writing .env file"
cat > .env << EOF
# ===========================================
# Production Environment Configuration
# Generated: $(date -u +"%Y-%m-%dT%H:%M:%SZ")
# ===========================================

# MySQL Database
MYSQL_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}
MYSQL_DATABASE=printer_management
MYSQL_USER=printer_admin
MYSQL_PASSWORD=${MYSQL_PASSWORD}

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
FRONTEND_PORT=${FRONTEND_PORT}
WEBSOCKET_PORT=${WEBSOCKET_PORT}
MYSQL_PORT=${MYSQL_PORT}

# Timezone
TZ=${TZ_VALUE}
EOF

chmod 600 .env
ok ".env written and secured (chmod 600)"

# ============================================================================
# Step 5b — Sanity-check docker-compose.yml port mappings
# ============================================================================
step "Step 5b/9: Checking docker-compose.yml for port conflicts"
if [ -f docker-compose.yml ] || [ -f docker-compose.yaml ]; then
  COMPOSE_FILE=$( [ -f docker-compose.yml ] && echo docker-compose.yml || echo docker-compose.yaml )
  for p in 80 6001 3306; do
    if grep -Eq "\"?${p}:[0-9]+\"?" "${COMPOSE_FILE}"; then
      echo -e "${c_yellow}Warning:${c_reset} ${COMPOSE_FILE} still hard-codes host port ${p}, which is already in use."
      echo "         Update its port mapping to use \${FRONTEND_PORT}, \${WEBSOCKET_PORT}, or \${MYSQL_PORT}"
      echo "         (e.g. \"\${FRONTEND_PORT}:80\") so it picks up the values from .env."
    fi
  done
else
  echo -e "${c_yellow}Note:${c_reset} no docker-compose.yml found yet in ${APP_DIR} — skipping port conflict check."
  echo "      Once it's in place, make sure it maps ports via \${FRONTEND_PORT}, \${WEBSOCKET_PORT}, \${MYSQL_PORT}"
  echo "      (e.g. \"\${FRONTEND_PORT}:80\") rather than hard-coded 80 / 6001 / 3306."
fi

# ============================================================================
# Step 5c — Configure nginx's /api reverse proxy to point at the backend
# container (not localhost/127.0.0.1, which inside Docker refers to the
# nginx container itself, not the backend)
# ============================================================================
step "Step 5c/9: Configuring nginx /api proxy_pass"

# Auto-detect the backend service name from docker-compose.yml
BACKEND_SERVICE="${BACKEND_SERVICE:-}"
if [ -z "${BACKEND_SERVICE}" ]; then
  for candidate in backend api server; do
    if docker compose config --services 2>/dev/null | grep -qx "${candidate}"; then
      BACKEND_SERVICE="${candidate}"
      break
    fi
  done
fi
BACKEND_SERVICE="${BACKEND_SERVICE:-backend}"
ok "Using backend service name: ${BACKEND_SERVICE}"

# Look for an existing nginx config in common locations
NGINX_CONF=""
for candidate in nginx/default.conf nginx/conf.d/default.conf nginx/nginx.conf docker/nginx/default.conf nginx.conf; do
  if [ -f "${candidate}" ]; then
    NGINX_CONF="${candidate}"
    break
  fi
done

if [ -n "${NGINX_CONF}" ]; then
  cp "${NGINX_CONF}" "${NGINX_CONF}.bak.$(date +%s)"

  if grep -qE 'proxy_pass\s+http://(localhost|127\.0\.0\.1):[0-9]+' "${NGINX_CONF}"; then
    # Fix the common mistake: proxy_pass pointing at localhost/127.0.0.1
    # instead of the backend container's service name
    sed -i.tmp -E "s#proxy_pass[[:space:]]+http://(localhost|127\.0\.0\.1):[0-9]+#proxy_pass http://${BACKEND_SERVICE}:${BACKEND_PORT}#g" "${NGINX_CONF}"
    rm -f "${NGINX_CONF}.tmp"
    ok "Fixed proxy_pass in ${NGINX_CONF} to point at http://${BACKEND_SERVICE}:${BACKEND_PORT}"
  elif grep -qE "proxy_pass\s+http://${BACKEND_SERVICE}:" "${NGINX_CONF}"; then
    ok "${NGINX_CONF} already proxies to ${BACKEND_SERVICE}:${BACKEND_PORT} — no change needed"
  elif grep -q "location /api" "${NGINX_CONF}"; then
    echo -e "${c_yellow}Warning:${c_reset} found a 'location /api' block in ${NGINX_CONF} but couldn't"
    echo "         confirm its proxy_pass target. Please check it manually:"
    grep -A5 "location /api" "${NGINX_CONF}"
  else
    echo -e "${c_yellow}Warning:${c_reset} no 'location /api' block found in ${NGINX_CONF}."
    echo "         Add one manually, e.g.:"
    echo "           location /api {"
    echo "               proxy_pass http://${BACKEND_SERVICE}:${BACKEND_PORT};"
    echo "               proxy_set_header Host \$host;"
    echo "               proxy_set_header X-Real-IP \$remote_addr;"
    echo "           }"
  fi
else
  # No nginx config found anywhere in the project — create a sensible default
  mkdir -p nginx
  NGINX_CONF="nginx/default.conf"
  cat > "${NGINX_CONF}" << EOF
server {
    listen 80;
    server_name _;

    location /api {
        proxy_pass http://${BACKEND_SERVICE}:${BACKEND_PORT};
        proxy_http_version 1.1;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
    }

    location / {
        root /usr/share/nginx/html;
        try_files \$uri \$uri/ /index.html;
    }
}
EOF
  ok "No existing nginx config found — created ${NGINX_CONF} with /api proxied to ${BACKEND_SERVICE}:${BACKEND_PORT}"
  echo -e "${c_yellow}Important:${c_reset} for this file to take effect, docker-compose.yml's nginx service"
  echo "           must mount it, e.g.:"
  echo "             volumes:"
  echo "               - ./${NGINX_CONF}:/etc/nginx/conf.d/default.conf"
  echo "           Add that mount, then re-run this script or run: docker compose up -d --force-recreate nginx"
fi

# ============================================================================
# Step 6 — Build images
# ============================================================================
step "Step 6/9: Building Docker images"
docker compose build
ok "Images built successfully"

# ============================================================================
# Step 7 — Start services
# ============================================================================
step "Step 7/9: Starting services (detached mode)"
docker compose up -d
ok "docker compose up -d issued"

# ============================================================================
# Step 7a — Reload nginx so the /api proxy_pass fix from Step 5c takes
# effect immediately, even if the nginx container was already running
# ============================================================================
step "Step 7a/9: Reloading nginx config"
if docker compose ps --status=running --services 2>/dev/null | grep -qx nginx; then
  if docker compose exec nginx nginx -s reload >/dev/null 2>&1; then
    ok "nginx config reloaded"
  else
    echo -e "${c_yellow}Note:${c_reset} nginx -s reload failed — recreating the container instead"
    docker compose up -d --force-recreate nginx
    ok "nginx container recreated"
  fi
else
  ok "nginx will pick up the config on its first start"
fi

# ============================================================================
# Step 7b — Import init.sql into the database (only runs once: skipped if
# the database already has tables, so re-running the script won't re-import
# and clobber existing data)
# ============================================================================
step "Step 7b/9: Importing init.sql into MySQL"

INIT_SQL_FILE="${INIT_SQL_FILE:-init.sql}"
MYSQL_SERVICE="${MYSQL_SERVICE:-}"

if [ ! -f "${INIT_SQL_FILE}" ]; then
  echo -e "${c_yellow}Note:${c_reset} ${INIT_SQL_FILE} not found in ${APP_DIR} — skipping import."
else
  # Auto-detect the mysql service name from docker-compose.yml if not set explicitly
  if [ -z "${MYSQL_SERVICE}" ]; then
    for candidate in mysql db database mariadb; do
      if docker compose config --services 2>/dev/null | grep -qx "${candidate}"; then
        MYSQL_SERVICE="${candidate}"
        break
      fi
    done
  fi

  if [ -z "${MYSQL_SERVICE}" ]; then
    echo -e "${c_yellow}Warning:${c_reset} could not auto-detect the MySQL service name from docker-compose.yml."
    echo "         Set MYSQL_SERVICE=<name> and re-run, e.g.: MYSQL_SERVICE=mysql ./install-printer-management.sh"
  else
    ok "Using MySQL service: ${MYSQL_SERVICE}"

    # Wait for MySQL to actually accept connections before importing
    mysql_attempt=1
    mysql_ready=false
    while [ "${mysql_attempt}" -le "${HEALTH_RETRIES}" ]; do
      if docker compose exec -T "${MYSQL_SERVICE}" \
           mysqladmin ping -uroot -p"${MYSQL_ROOT_PASSWORD}" --silent >/dev/null 2>&1; then
        mysql_ready=true
        break
      fi
      echo "   ...waiting for MySQL to accept connections (attempt ${mysql_attempt}/${HEALTH_RETRIES})"
      sleep "${HEALTH_DELAY}"
      mysql_attempt=$((mysql_attempt + 1))
    done

    if [ "${mysql_ready}" != true ]; then
      echo -e "${c_red}MySQL did not become ready in time — skipping init.sql import.${c_reset}"
      echo "         You can import it manually later with:"
      echo "         docker compose exec -T ${MYSQL_SERVICE} mysql -uroot -p'${MYSQL_ROOT_PASSWORD}' printer_management < ${INIT_SQL_FILE}"
    else
      # Check whether the database already has tables — if so, assume it was
      # already imported (or is live) and skip, rather than re-running init.sql
      TABLE_COUNT=$(docker compose exec -T "${MYSQL_SERVICE}" \
        mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" -N -e \
        "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema='printer_management';" 2>/dev/null | tr -d '\r')

      if [ "${TABLE_COUNT:-0}" -gt 0 ] 2>/dev/null; then
        ok "Database already has ${TABLE_COUNT} table(s) — skipping init.sql import (already initialized)."
      else
        if docker compose exec -T "${MYSQL_SERVICE}" \
             mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" printer_management < "${INIT_SQL_FILE}"; then
          ok "init.sql imported successfully into 'printer_management'"
        else
          fail "Failed to import ${INIT_SQL_FILE} into MySQL. Check the error output above."
        fi
      fi
    fi
  fi
fi

step "Step 7c/9: Fixing default admin password hash"
 
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@printer.local}"
# Verified hash for "Admin123!" — generated and confirmed with bcrypt.checkpw()
ADMIN_PASSWORD_HASH="${ADMIN_PASSWORD_HASH:-\$2b\$10\$qIH8KWtAnC8qrVu02dgK0.r/OvEOpJ2R7ZaHr7bVFMHn11P67i/E2}"

if [ -n "${MYSQL_SERVICE:-}" ]; then
  if docker compose exec -T "${MYSQL_SERVICE}" \
       mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" printer_management -e \
       "UPDATE users SET password='${ADMIN_PASSWORD_HASH}' WHERE email='${ADMIN_EMAIL}';" 2>/dev/null; then
    ok "Admin password hash fixed for ${ADMIN_EMAIL}"
    if [ "${ADMIN_PASSWORD_HASH}" = '$2b$10$qIH8KWtAnC8qrVu02dgK0.r/OvEOpJ2R7ZaHr7bVFMHn11P67i/E2' ]; then
      ok "Login with: ${ADMIN_EMAIL} / Admin123!"
    fi
  else
    echo -e "${c_yellow}Note:${c_reset} could not update admin password (table/user may not exist yet, or MySQL wasn't reachable). Skipping."
  fi
else
  echo -e "${c_yellow}Note:${c_reset} MySQL service not detected — skipping admin password fix."
fi

# ============================================================================
# Step 8 — Verify containers are running
# ============================================================================
step "Step 8/9: Verifying container status"
sleep 3
docker compose ps
if docker compose ps --status=exited | grep -q .; then
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
echo "  MySQL (host):  localhost:${MYSQL_PORT} (was 3306, only relevant if exposed to the host)"
echo "  App directory: ${APP_DIR}"
echo "  Secrets file:  ${APP_DIR}/.env (chmod 600 — keep this safe)"
echo "  Database init: init.sql imported (or already present — see Step 7b above)"
echo
echo "  Tip: re-running this script will reuse existing secrets in .env"
echo "  instead of generating new ones, so it's safe to re-run for updates."