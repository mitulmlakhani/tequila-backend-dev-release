#!/bin/bash
set -e

BASE="/home/tequilapos/tequila-backend"
REPO="$BASE"
RELEASES="$BASE/../releases"
SHARED="$BASE/../shared"
CURRENT="$BASE/../current"
LOCKFILE="$BASE/../deploy.lock"

PHP=$(which php)
COMPOSER=$(which composer)

# Prevent concurrent deploy
if [ -f "$LOCKFILE" ]; then
    echo "Deploy already running."
    exit 1
fi

touch $LOCKFILE
trap "rm -f $LOCKFILE" EXIT

echo "Fetching updates..."

cd $REPO
git fetch --tags --quiet


# Get latest tag from remote
LATEST_TAG=$(git ls-remote --tags origin | awk -F/ '/refs\/tags\// {print $3}' | sort -V | tail -n 1)
echo "Latest remote tag: $LATEST_TAG"

# File to store last deployed tag
DEPLOYED_TAG_FILE="$BASE/../.deployed_tag"
DEPLOYED_TAG=""
if [ -f "$DEPLOYED_TAG_FILE" ]; then
    DEPLOYED_TAG=$(cat "$DEPLOYED_TAG_FILE")
fi
echo "Last deployed tag: $DEPLOYED_TAG"

if [ "$LATEST_TAG" == "$DEPLOYED_TAG" ]; then
    echo "Success: Project is already at the latest version ($LATEST_TAG)."
    exit 0
fi

TIMESTAMP=$(date +"%Y%m%d%H%M%S")
NEW_RELEASE="$RELEASES/$TIMESTAMP"

echo "Deploying $LATEST_TAG"

mkdir -p "$NEW_RELEASE"
git archive $LATEST_TAG | tar -x -C "$NEW_RELEASE"

cd $NEW_RELEASE


echo "Link shared resources"
ln -s $SHARED/.env .env
rm -rf storage
ln -s $SHARED/storage storage

echo "Fixing permissions..."

# Automatically fix permissions for the new release
sudo chown -R www-data:www-data "$NEW_RELEASE/storage" "$NEW_RELEASE/bootstrap/cache"
sudo chmod -R 775 "$NEW_RELEASE/storage" "$NEW_RELEASE/bootstrap/cache"

echo "Install dependencies"

$COMPOSER install \
  --no-dev \
  --optimize-autoloader \
  --no-interaction \
  --prefer-dist

echo "Laravel optimizations"

ENV_PATH="$SHARED/.env"
sudo chown tequilapos:www-data "$ENV_PATH"
sudo chmod 640 "$ENV_PATH"

cd $CURRENT

$PHP artisan config:cache
$PHP artisan view:cache

echo "Running migrations"

$PHP artisan migrate --force

echo "Health check"

if ! $PHP artisan about > /dev/null; then
    echo "Health check failed!"
    rm -rf $NEW_RELEASE
    exit 1
fi

echo "Switching release"


# Ensure $CURRENT is a symlink for atomic switch
if [ ! -L "$CURRENT" ] && [ -e "$CURRENT" ]; then
    rm -rf "$CURRENT"
fi
ln -sfn "$NEW_RELEASE" "$CURRENT"

echo "Restart queues"

sudo supervisorctl restart all

echo "Reload PHP"

sudo systemctl reload php8.3-fpm

echo "Cleaning old releases"

cd $RELEASES
ls -dt */ | tail -n +6 | xargs rm -rf

echo "$LATEST_TAG" > "$DEPLOYED_TAG_FILE"
echo "Deploy complete ($LATEST_TAG)"