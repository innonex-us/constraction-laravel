#!/bin/bash

echo "🚀 Starting Laravel Construction Website..."

# Wait for MySQL/MariaDB to be ready
echo "⏳ Waiting for MySQL to be ready..."
MYSQL_ADMIN_CMD=$(command -v mysqladmin || command -v mariadb-admin)
if [ -z "$MYSQL_ADMIN_CMD" ]; then
    echo "❌ mysqladmin or mariadb-admin not found."
    exit 1
fi
while ! "$MYSQL_ADMIN_CMD" ping -h"$DB_HOST" --silent; do
    sleep 1
done

echo "✅ MySQL is ready!"

# Run migrations
echo "📦 Running database migrations..."
php artisan migrate --force

# Clear and cache config for production
if [ "$APP_ENV" = "production" ]; then
    echo "🔧 Optimizing for production..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
fi

# Set proper permissions
echo "🔒 Setting file permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/bootstrap/cache

# Add Laravel scheduler to crontab
echo "⏰ Setting up Laravel scheduler..."
echo "* * * * * cd /var/www/html && php artisan schedule:run >> /dev/null 2>&1" > /etc/cron.d/laravel-scheduler
chmod 0644 /etc/cron.d/laravel-scheduler
crontab /etc/cron.d/laravel-scheduler

# Create log directory for supervisor
mkdir -p /var/log/supervisor

echo "🎉 Laravel Construction Website is ready!"

# Start supervisor (which starts Apache and cron)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/laravel.conf
