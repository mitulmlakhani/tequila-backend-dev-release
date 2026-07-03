#!/usr/bin/env bash
# =============================================================================
# Tequila POS – Lando post-start init script
# Runs on every `lando start` to ensure the app is ready.
# =============================================================================
set -euo pipefail

cd /app

echo ""
echo "🌮  Initializing Tequila POS …"
echo "─────────────────────────────────────────"

# ── 1. Environment file ─────────────────────────────────────────────────────
if [ ! -f .env ]; then
  echo "📋  Creating .env from .env.example …"
  cp .env.example .env
fi

# ── 2. Application key ──────────────────────────────────────────────────────
APP_KEY=$(grep '^APP_KEY=' .env | cut -d'=' -f2-)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
  echo "🔑  Generating application key …"
  php artisan key:generate --no-interaction --force
fi

# ── 3. Wait for database ────────────────────────────────────────────────────
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
    echo "    Check that the database service is running: lando info"
    exit 1
  fi
  echo "    Waiting for MySQL … (attempt $TRIES/$MAX_TRIES)"
  sleep 2
done
echo "✅  Database connected!"


# ── 5. Storage link ─────────────────────────────────────────────────────────
echo "🔗  Creating storage link …"
php artisan storage:link 2>/dev/null || true

# ── 6. Clear caches ─────────────────────────────────────────────────────────
echo "🧹  Clearing caches …"
php artisan config:clear  --no-interaction
php artisan cache:clear   --no-interaction
php artisan route:clear   --no-interaction
php artisan view:clear    --no-interaction

# ── 7. Create log files for supervisor ───────────────────────────────────────
mkdir -p storage/logs
touch storage/logs/queue-worker.log
touch storage/logs/scheduler.log
touch storage/logs/echo-server.log

# ── 8. Trust Lando SSL certificate inside container ─────────────────────────
echo "🔒 Registering Lando SSL certificates..."
bash /app/.lando/trust-certs.sh

# ── Done! ────────────────────────────────────────────────────────────────────
echo ""
echo "─────────────────────────────────────────"
echo "✅  Tequila POS is ready!"
echo ""
echo "   🌮  App:      https://tequila.lndo.site"
echo "   🗄️   Adminer:  http://adminer-tequila.lndo.site"
echo "   📧  MailHog:   http://mail-tequila.lndo.site"
echo "   🔌  Echo WS:  ws://localhost:6000"
echo "   🗃️   MySQL:    localhost:33060  (user: tequila / pass: tequila / db: tequila_pos)"
echo "   📦  Redis:    localhost:63790"
echo ""
echo "   Quick commands:"
echo "     lando artisan          – Run Artisan commands"
echo "     lando fresh            – migrate:fresh --seed"
echo "     lando tinker           – Laravel Tinker REPL"
echo "     lando test             – Run PHPUnit tests"
echo "     lando worker-status    – Check queue/scheduler status"
echo ""
