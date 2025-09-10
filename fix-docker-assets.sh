#!/bin/bash

# fix-docker-assets.sh - Fix CSS/JS asset loading issues in Docker

echo "🔧 Fixing Docker Asset Loading Issues"
echo "====================================="

# Function to print colored output
print_status() {
    echo -e "\n🔵 $1"
}

print_success() {
    echo -e "\n✅ $1"
}

print_error() {
    echo -e "\n❌ $1"
}

# Check if containers are running
if ! docker ps | grep -q "construction_app"; then
    print_error "Construction app container is not running!"
    echo "Please run: ./docker-deploy.sh prod"
    exit 1
fi

print_status "Checking current asset status..."

# Check if build directory exists in container
docker exec construction_app ls -la public/build/ || {
    print_error "Build directory missing in container!"
    
    print_status "Building assets locally and copying to container..."
    
    # Build assets locally if not already built
    if [ ! -d "public/build" ]; then
        print_status "Running npm build locally..."
        npm ci
        npm run build
    fi
    
    # Copy built assets to container
    print_status "Copying assets to container..."
    docker cp public/build construction_app:/var/www/html/public/
    
    print_success "Assets copied to container!"
}

# Check Vite manifest
print_status "Checking Vite manifest..."
docker exec construction_app cat public/build/manifest.json || {
    print_error "Vite manifest is missing or corrupted!"
    
    print_status "Rebuilding assets..."
    npm run build
    docker cp public/build construction_app:/var/www/html/public/
}

# Fix file permissions
print_status "Fixing file permissions..."
docker exec construction_app chown -R www-data:www-data /var/www/html/public/build
docker exec construction_app chmod -R 755 /var/www/html/public/build

# Clear Laravel caches
print_status "Clearing Laravel caches..."
docker exec construction_app php artisan cache:clear
docker exec construction_app php artisan config:clear
docker exec construction_app php artisan view:clear

# Recache for production
print_status "Recaching for production..."
docker exec construction_app php artisan config:cache
docker exec construction_app php artisan route:cache
docker exec construction_app php artisan view:cache

# Check asset URLs in container
print_status "Checking asset configuration..."
docker exec construction_app php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
echo 'APP_URL: ' . config('app.url') . PHP_EOL;
echo 'ASSET_URL: ' . config('app.asset_url') . PHP_EOL;
echo 'Vite manifest exists: ' . (file_exists('public/build/manifest.json') ? 'YES' : 'NO') . PHP_EOL;
"

# Test asset loading
print_status "Testing asset access..."
docker exec construction_app curl -I http://localhost/build/manifest.json 2>/dev/null | head -1

print_success "Asset fix completed!"
echo ""
echo "🌐 Try accessing: http://localhost:8080"
echo "🔄 Or via Nginx: http://localhost:8081"
echo ""
echo "If CSS still not loading, check browser console for specific errors."
