#!/bin/bash

# cPanel Laravel Deployment Script
# This script should be run after uploading files to cPanel

echo "🚀 Starting Laravel deployment for cPanel..."

# Set permissions
echo "📁 Setting file permissions..."
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Install/Update Composer dependencies (production)
echo "📦 Installing Composer dependencies..."
composer install --optimize-autoloader --no-dev

# Create symlink for storage (if not exists)
if [ ! -L "public/storage" ]; then
    echo "🔗 Creating storage symlink..."
    php artisan storage:link
fi

# Generate application key (if not set)
echo "🔑 Checking application key..."
php artisan key:generate --show

# Run database migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# Clear and cache config for production
echo "⚡ Optimizing application..."
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

# Clear application cache
php artisan cache:clear

echo "✅ Deployment completed successfully!"
echo ""
echo "🔧 Don't forget to:"
echo "   1. Update your .env file with correct database credentials"
echo "   2. Set APP_URL to your domain"
echo "   3. Set APP_DEBUG=false for production"
echo "   4. Configure your cPanel to point to the 'public' directory"
echo ""
