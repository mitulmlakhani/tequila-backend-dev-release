#!/usr/bin/env bash
# =============================================================================
# Tequila POS – Docker Entrypoint & Init Script
# Runs inside appserver on startup to initialize the environment and start services.
# =============================================================================
set -uo pipefail

cd /var/www/html

echo ""
echo "🌮  Initializing Tequila POS (Docker)…"
echo "─────────────────────────────────────────"

# ── 1. Environment file ─────────────────────────────────────────────────────
if [ ! -f .env ]; then
  echo "📋  Creating .env from .env.example …"
  cp .env.example .env
fi

# ── 2. Ensure dependencies if missing ────────────────────────────────────────
if [ ! -d vendor ]; then
  echo "📦  Installing Composer dependencies …"
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# ── 3. Application key ──────────────────────────────────────────────────────
APP_KEY=$(grep '^APP_KEY=' .env | cut -d'=' -f2- || true)
if ! grep -q '^APP_KEY=base64:' .env; then
  echo "🔑  Generating application key …"
  php artisan key:generate --force --no-interaction
fi

if [ ! -d node_modules ]; then
  echo "📦  Installing NPM dependencies …"
  PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true npm install
fi

# ── 4. Wait for database ────────────────────────────────────────────────────
echo "⏳  Waiting for database …"
MAX_TRIES=30
TRIES=0
until php -r "
  try {
    new PDO(
      'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
      getenv('DB_USERNAME'),
      getenv('DB_PASSWORD')
    );
    echo 'connected';
  } catch (Exception \$e) {
    exit(1);
  }
" 2>/dev/null; do
  TRIES=$((TRIES + 1))
  if [ "$TRIES" -ge "$MAX_TRIES" ]; then
    echo "❌  Could not connect to database after $MAX_TRIES attempts."
    break
  fi
  echo "    Waiting for MySQL … (attempt $TRIES/$MAX_TRIES)"
  sleep 2
done
if [ "$TRIES" -lt "$MAX_TRIES" ]; then
  echo "✅  Database connected!"
fi

# ── 5. Storage link & permissions ───────────────────────────────────────────
echo "🔗  Creating storage link & preparing logs …"
php artisan storage:link 2>/dev/null || true
mkdir -p storage/app/public storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 777 storage bootstrap/cache 2>/dev/null || true

# ── 6. Clear caches ─────────────────────────────────────────────────────────
echo "🧹  Clearing caches …"
php artisan config:clear  --no-interaction || true
php artisan cache:clear   --no-interaction || true
php artisan route:clear   --no-interaction || true
php artisan view:clear    --no-interaction || true

# ── 7. Create log files for supervisor & cron ────────────────────────────────
touch storage/logs/queue-worker.log storage/logs/scheduler.log storage/logs/echo-server.log storage/logs/laravel.log storage/logs/cron.log storage/logs/auto-update.log
chown www-data:www-data storage/logs/*.log 2>/dev/null || true
chmod 666 storage/logs/*.log 2>/dev/null || true

# ── 8. Configure and start supervisor workers ────────────────────────────────
echo "🚀  Starting supervisor background workers (Echo Server, Queue, Scheduler)..."
mkdir -p /etc/supervisor/conf.d
cp /var/www/html/docker/supervisord.conf /etc/supervisor/conf.d/tequila-workers.conf
supervisord -c /etc/supervisor/supervisord.conf 2>/dev/null || true
supervisorctl -c /etc/supervisor/supervisord.conf reread 2>/dev/null || true
supervisorctl -c /etc/supervisor/supervisord.conf update 2>/dev/null || true
supervisorctl -c /etc/supervisor/supervisord.conf start all 2>/dev/null || true

echo "─────────────────────────────────────────"
echo "✅  Tequila POS initialization complete!"
echo "🚀  Starting command: $@"
echo "─────────────────────────────────────────"

exec "$@"
