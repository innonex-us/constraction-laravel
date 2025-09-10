#!/bin/bash

# server-asset-fix.sh - Fix CSS/JS loading on server deployment

echo "🔧 Server Asset Loading Fix"
echo "=========================="

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

print_warning() {
    echo -e "\n⚠️  $1"
}

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    print_error "Not in a Laravel project directory!"
    echo "Please navigate to your Laravel project directory first."
    exit 1
fi

print_status "Checking current environment..."

# Check if .env exists
if [ ! -f ".env" ]; then
    print_error ".env file not found!"
    echo "Please ensure .env file exists with proper configuration."
    exit 1
fi

# Check APP_URL and ASSET_URL
APP_URL=$(grep "^APP_URL=" .env | cut -d'=' -f2)
ASSET_URL=$(grep "^ASSET_URL=" .env | cut -d'=' -f2)

echo "Current APP_URL: $APP_URL"
echo "Current ASSET_URL: $ASSET_URL"

# Check if public/build directory exists
print_status "Checking asset build directory..."
if [ ! -d "public/build" ]; then
    print_error "public/build directory not found!"
    
    print_warning "Assets need to be built. Options:"
    echo "1. Run locally: npm run build (then upload public/build)"
    echo "2. Install Node.js on server and run: npm ci && npm run build"
    
    read -p "Do you want to try installing Node.js and building? [y/N]: " -n 1 -r
    echo
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        # Try to install Node.js (Ubuntu/Debian)
        if command -v apt-get &> /dev/null; then
            print_status "Installing Node.js..."
            curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
            sudo apt-get install -y nodejs
        elif command -v yum &> /dev/null; then
            print_status "Installing Node.js on CentOS/RHEL..."
            curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
            sudo yum install -y nodejs
        else
            print_error "Could not install Node.js automatically. Please install manually."
            exit 1
        fi
        
        # Install dependencies and build
        print_status "Installing npm dependencies..."
        npm ci
        
        print_status "Building assets..."
        npm run build
    else
        print_warning "Please build assets locally and upload the public/build directory."
        exit 1
    fi
fi

# Check manifest file
print_status "Checking Vite manifest..."
if [ ! -f "public/build/manifest.json" ]; then
    print_error "Vite manifest.json not found!"
    print_warning "Try rebuilding assets: npm run build"
    exit 1
fi

print_success "Manifest file found!"

# Check file permissions
print_status "Checking and fixing file permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data public/build
sudo chmod -R 755 public/build

# Check if using Apache
if command -v apache2 &> /dev/null || command -v httpd &> /dev/null; then
    print_status "Apache detected - checking .htaccess..."
    
    if [ ! -f "public/.htaccess" ]; then
        print_warning "public/.htaccess not found! Creating default..."
        cat > public/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOF
        print_success "Created default .htaccess file"
    fi
    
    # Check if mod_rewrite is enabled
    print_status "Checking Apache modules..."
    if command -v a2enmod &> /dev/null; then
        sudo a2enmod rewrite
        sudo a2enmod headers
        sudo systemctl restart apache2
        print_success "Apache modules enabled and restarted"
    fi
fi

# Clear Laravel caches
print_status "Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Recache for production
print_status "Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check storage link
print_status "Checking storage link..."
if [ ! -L "public/storage" ]; then
    print_status "Creating storage symlink..."
    php artisan storage:link
fi

# Test asset loading
print_status "Testing asset configuration..."
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
echo 'APP_URL: ' . config('app.url') . PHP_EOL;
echo 'ASSET_URL: ' . config('app.asset_url', 'Not set') . PHP_EOL;
echo 'Build directory exists: ' . (is_dir('public/build') ? 'YES' : 'NO') . PHP_EOL;
echo 'Manifest exists: ' . (file_exists('public/build/manifest.json') ? 'YES' : 'NO') . PHP_EOL;
if (file_exists('public/build/manifest.json')) {
    \$manifest = json_decode(file_get_contents('public/build/manifest.json'), true);
    echo 'Manifest entries: ' . count(\$manifest) . PHP_EOL;
    echo 'CSS files: ' . count(array_filter(\$manifest, function(\$item) { return strpos(\$item['file'], '.css') !== false; })) . PHP_EOL;
    echo 'JS files: ' . count(array_filter(\$manifest, function(\$item) { return strpos(\$item['file'], '.js') !== false; })) . PHP_EOL;
}
"

print_success "Server asset fix completed!"
echo ""
echo "🌐 Test your website now!"
echo ""
print_warning "If CSS still not loading:"
echo "1. Check browser console for 404 errors"
echo "2. Verify APP_URL matches your domain"
echo "3. Check web server configuration"
echo "4. Ensure public/build directory is accessible via web"
echo ""
echo "Common fixes:"
echo "- Update APP_URL in .env to match your domain"
echo "- Check if ASSET_URL is needed for CDN"
echo "- Verify web server document root points to public/"
