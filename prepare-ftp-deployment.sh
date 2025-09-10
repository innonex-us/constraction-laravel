#!/bin/bash

# prepare-ftp-deployment.sh
# This script prepares your Laravel project for FTP deployment to cPanel

echo "🚀 Preparing Laravel project for FTP deployment..."

# Step 1: Install dependencies locally
echo "📦 Installing PHP dependencies..."
composer install --optimize-autoloader --no-dev

if [ $? -ne 0 ]; then
    echo "❌ Composer install failed. Please check your composer.json file."
    exit 1
fi

# Step 2: Install Node dependencies and build assets
echo "🎨 Building production assets..."
npm ci
npm run build

if [ $? -ne 0 ]; then
    echo "❌ Asset build failed. Please check your package.json and vite.config.js."
    exit 1
fi

# Step 3: Generate application key
echo "🔑 Generating application key..."
APP_KEY=$(php artisan key:generate --show)
echo "Generated APP_KEY: $APP_KEY"
echo "⚠️  IMPORTANT: Save this key for your production .env file!"

# Step 4: Create deployment package
echo "📁 Creating deployment package..."
DEPLOY_DIR="../deployment-package"

# Remove existing deployment package
if [ -d "$DEPLOY_DIR" ]; then
    rm -rf "$DEPLOY_DIR"
fi

mkdir -p "$DEPLOY_DIR"

# Copy all files except development-only files
echo "📋 Copying project files..."
rsync -av --progress \
    --exclude='node_modules' \
    --exclude='.git' \
    --exclude='.env' \
    --exclude='.env.example' \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='tests' \
    --exclude='*.md' \
    --exclude='deploy-cpanel.sh' \
    --exclude='build-production.sh' \
    --exclude='prepare-ftp-deployment.sh' \
    ./ "$DEPLOY_DIR"

# Step 5: Set up production environment file
echo "⚙️  Setting up production environment..."
cp .env.cpanel.example "$DEPLOY_DIR/.env"

# Replace the APP_KEY in the production .env file
if [[ "$OSTYPE" == "darwin"* ]]; then
    # macOS
    sed -i '' "s/APP_KEY=/APP_KEY=$APP_KEY/" "$DEPLOY_DIR/.env"
else
    # Linux
    sed -i "s/APP_KEY=/APP_KEY=$APP_KEY/" "$DEPLOY_DIR/.env"
fi

# Step 6: Create necessary directories
echo "📂 Creating required directories..."
mkdir -p "$DEPLOY_DIR/storage/framework/cache/data"
mkdir -p "$DEPLOY_DIR/storage/framework/sessions"
mkdir -p "$DEPLOY_DIR/storage/framework/views"
mkdir -p "$DEPLOY_DIR/storage/logs"
mkdir -p "$DEPLOY_DIR/bootstrap/cache"
mkdir -p "$DEPLOY_DIR/storage/app/public/images"
mkdir -p "$DEPLOY_DIR/storage/app/public/galleries"
mkdir -p "$DEPLOY_DIR/storage/app/public/projects"

# Step 7: Create cPanel setup script
echo "🔧 Creating cPanel setup script..."
cat > "$DEPLOY_DIR/setup-cpanel.php" << 'EOF'
<?php
// setup-cpanel.php - Run this file once via browser after FTP upload
echo "<h2>🚀 cPanel Laravel Setup</h2>";

// Set proper permissions (as much as possible via PHP)
$directories = [
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

echo "<h3>📁 Setting Directory Permissions</h3>";
foreach ($directories as $dir) {
    if (is_dir($dir)) {
        $result = chmod($dir, 0755);
        echo ($result ? "✅" : "❌") . " $dir<br>";
    } else {
        echo "⚠️  Directory not found: $dir<br>";
    }
}

// Create storage link if it doesn't exist
echo "<h3>🔗 Creating Storage Link</h3>";
if (!file_exists('public/storage')) {
    $target = '../storage/app/public';
    $link = 'public/storage';
    
    if (symlink($target, $link)) {
        echo "✅ Storage link created successfully<br>";
    } else {
        echo "❌ Failed to create storage link - create manually in cPanel File Manager<br>";
        echo "Create symlink from 'public/storage' to 'storage/app/public'<br>";
    }
} else {
    echo "✅ Storage link already exists<br>";
}

// Test database connection
echo "<h3>🗄️  Testing Database Connection</h3>";
if (file_exists('.env')) {
    $envData = file_get_contents('.env');
    
    // Simple env parser
    $dbHost = getEnvValue($envData, 'DB_HOST');
    $dbDatabase = getEnvValue($envData, 'DB_DATABASE');
    $dbUsername = getEnvValue($envData, 'DB_USERNAME');
    $dbPassword = getEnvValue($envData, 'DB_PASSWORD');
    
    if ($dbHost && $dbDatabase && $dbUsername) {
        try {
            $pdo = new PDO(
                "mysql:host=$dbHost;dbname=$dbDatabase",
                $dbUsername,
                $dbPassword,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            echo "✅ Database connection: SUCCESS<br>";
            echo "Connected to: $dbDatabase on $dbHost<br>";
        } catch (PDOException $e) {
            echo "❌ Database connection: FAILED<br>";
            echo "Error: " . $e->getMessage() . "<br>";
            echo "Please check your database credentials in .env file<br>";
        }
    } else {
        echo "⚠️  Database credentials not found in .env file<br>";
    }
} else {
    echo "❌ .env file not found<br>";
}

// Check if vendor directory exists
echo "<h3>📦 Checking Dependencies</h3>";
if (is_dir('vendor')) {
    echo "✅ Vendor directory found<br>";
    if (file_exists('vendor/autoload.php')) {
        echo "✅ Autoloader found<br>";
    } else {
        echo "❌ Autoloader missing<br>";
    }
} else {
    echo "❌ Vendor directory missing - please upload the vendor folder<br>";
}

// Check compiled assets
echo "<h3>🎨 Checking Compiled Assets</h3>";
if (is_dir('public/build')) {
    $files = scandir('public/build');
    $assetCount = count($files) - 2; // Remove . and ..
    echo "✅ Build directory found with $assetCount files<br>";
} else {
    echo "❌ Build directory missing - run 'npm run build' locally and upload public/build<br>";
}

// Helper function to read .env values
function getEnvValue($envContent, $key) {
    $pattern = "/^$key=(.*)$/m";
    if (preg_match($pattern, $envContent, $matches)) {
        return trim($matches[1], '"\'');
    }
    return null;
}

echo "<br><h3>🎉 Setup Complete!</h3>";
echo "<p><strong>Next Steps:</strong></p>";
echo "<ul>";
echo "<li>✅ Delete this setup-cpanel.php file</li>";
echo "<li>🌐 Visit your website homepage</li>";
echo "<li>👤 Access admin panel at /admin</li>";
echo "<li>📧 Test contact forms</li>";
echo "</ul>";
?>
EOF

# Step 8: Copy htaccess for domain routing
cp .htaccess.cpanel "$DEPLOY_DIR/"

# Step 9: Create deployment instructions
cat > "$DEPLOY_DIR/UPLOAD-INSTRUCTIONS.txt" << EOF
🚀 FTP UPLOAD INSTRUCTIONS

1. UPLOAD ALL FILES:
   - Upload ALL files and folders from this deployment-package to your cPanel hosting
   - Make sure to upload the 'vendor' folder completely (this may take time)
   - Upload the 'public/build' folder with all compiled assets

2. CONFIGURE DOMAIN:
   Option A: Point domain document root to 'public' folder in cPanel
   Option B: Copy .htaccess.cpanel to domain root as .htaccess

3. SETUP DATABASE:
   - Create MySQL database in cPanel
   - Create database user with full permissions
   - Update .env file with correct database credentials

4. RUN SETUP:
   - Visit: https://yourdomain.com/setup-cpanel.php
   - Follow the setup instructions
   - DELETE setup-cpanel.php after successful setup

5. TEST WEBSITE:
   - Visit homepage
   - Test admin panel: /admin
   - Test contact forms
   - Verify mobile responsiveness

⚠️  IMPORTANT:
- Your APP_KEY is already set in the .env file
- Update database credentials in .env before upload
- Set proper file permissions for storage folders
- Create storage symlink if setup script fails

Generated on: $(date)
EOF

# Step 10: Create database export
echo "🗄️  Exporting database structure..."
if [ -f "database/database.sqlite" ]; then
    php artisan schema:dump --no-interaction
    if [ -f "database/schema/mysql-schema.sql" ]; then
        cp database/schema/mysql-schema.sql "$DEPLOY_DIR/"
        echo "✅ Database schema exported to mysql-schema.sql"
    fi
fi

# Final summary
echo ""
echo "🎉 FTP Deployment Package Ready!"
echo "=================================="
echo ""
echo "📁 Deployment package created at: $DEPLOY_DIR"
echo "🔑 APP_KEY generated: $APP_KEY"
echo ""
echo "📋 Next Steps:"
echo "1. Update database credentials in $DEPLOY_DIR/.env"
echo "2. Upload all files from $DEPLOY_DIR to your cPanel hosting"
echo "3. Run setup-cpanel.php via browser"
echo "4. Delete setup-cpanel.php after setup"
echo ""
echo "📖 For detailed instructions, see: FTP-DEPLOYMENT-GUIDE.md"
echo ""
echo "⚠️  Don't forget to:"
echo "   - Upload the complete 'vendor' folder"
echo "   - Set correct database credentials"
echo "   - Configure domain document root or .htaccess"
echo ""
