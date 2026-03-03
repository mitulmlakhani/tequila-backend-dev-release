
#!/bin/bash

# --- CONFIGURATION ---
# Set the path to your Laravel project
PROJECT_PATH="./"

cd "$PROJECT_PATH" || { echo "Error: Could not enter project directory"; exit 1; }

echo "--- Checking for Updates ---"

# 1. Fetch latest info from remote
git fetch --tags --quiet

# 2. Get the latest tag name (sorted by version)
LATEST_TAG=$(git tag --sort=-v:refname | head -n 1)

if [ -z "$LATEST_TAG" ]; then
    echo "Error: No tags found in the repository."
    exit 1
fi

# 3. Get the current checked-out version
CURRENT_VERSION=$(git describe --tags --abbrev=0 2>/dev/null)

if [ "$LATEST_TAG" == "$CURRENT_VERSION" ]; then
    echo "Success: Project is already at the latest version ($LATEST_TAG)."
    exit 0
fi

echo "New version detected: $LATEST_TAG (Current: $CURRENT_VERSION)"
echo "Starting update process..."

# 4. Protect local changes (like accidental edits to tracked files)
# This will save local changes so the update doesn't fail.
git stash --quiet

# 5. Checkout the latest tag
git checkout "$LATEST_TAG" --quiet

# 6. Laravel Production Optimizations
echo "Running Laravel optimizations..."

# Install production dependencies
composer install --no-dev --optimize-autoloader --no-interaction --quiet

# Run database migrations
php artisan migrate

# Clear and refresh all caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue if you use one
# php artisan queue:restart

echo "--- SUCCESS: Updated to $LATEST_TAG ---"