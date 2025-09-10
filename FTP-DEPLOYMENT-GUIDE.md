# FTP Deployment Guide for cPanel

This guide is specifically for deploying your Laravel construction website using **FTP access only** (no SSH required).

## 🚨 Important: FTP-Only Limitations

Since you don't have SSH access, you'll need to:
- Build everything locally before uploading
- Upload the `vendor` folder (since you can't run `composer install` on server)
- Manually create the application key
- Use cPanel tools for database operations

## 📋 Pre-Deployment Requirements

### Local Environment Setup
- PHP 8.4+ installed locally
- Composer installed locally
- Node.js and NPM installed locally
- FTP client (FileZilla, WinSCP, or similar)

### Hosting Account Setup
- cPanel hosting with PHP 8.4+
- MySQL database created in cPanel
- Database user with full permissions
- FTP credentials from your hosting provider

## 🛠️ Step 1: Prepare Project Locally

### 1.1 Install All Dependencies Locally
```bash
# Install PHP dependencies (this creates the vendor folder)
composer install --optimize-autoloader --no-dev

# Install Node dependencies and build assets
npm ci
npm run build
```

### 1.2 Generate Application Key
```bash
# Generate a unique application key
php artisan key:generate --show
```
**Important**: Copy this key! You'll need it for your production `.env` file.

### 1.3 Configure Production Environment
```bash
# Copy the production environment template
cp .env.cpanel.example .env.production
```

Edit `.env.production` with your production settings:
```env
APP_NAME="Your Construction Company"
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_cpanel_database_name
DB_USERNAME=your_cpanel_database_user
DB_PASSWORD=your_cpanel_database_password

# Email settings (get from your hosting provider)
MAIL_MAILER=smtp
MAIL_HOST=your-hosting-smtp-server
MAIL_PORT=587
MAIL_USERNAME=your-email@yourdomain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com

# File upload settings
UPLOAD_MAX_SIZE=10240
IMAGE_MAX_SIZE=5120

# Session and cache settings
SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

## 📁 Step 2: Prepare Files for Upload

### 2.1 Create Upload Package
Create a temporary folder with the correct structure:

```bash
# Create a deployment folder
mkdir ../deployment-package
cd ../deployment-package

# Copy all necessary files (excluding development files)
rsync -av --exclude='node_modules' \
          --exclude='.git' \
          --exclude='.env' \
          --exclude='storage/logs/*' \
          --exclude='storage/framework/cache/*' \
          --exclude='storage/framework/sessions/*' \
          --exclude='storage/framework/views/*' \
          ../constraction-laravel/ ./

# Copy your production environment file
cp .env.production .env

# Create necessary directories
mkdir -p storage/framework/cache/data
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Set up storage structure
mkdir -p storage/app/public/images
mkdir -p storage/app/public/galleries
mkdir -p storage/app/public/projects
```

### 2.2 Create cPanel-Specific Files

Create `setup-cpanel.php` in your deployment package:
```php
<?php
// setup-cpanel.php - Run this file once via browser after FTP upload

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

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        chmod($dir, 0755);
        echo "Set permissions for: $dir<br>";
    }
}

// Create storage link if it doesn't exist
if (!file_exists('public/storage')) {
    $target = '../storage/app/public';
    $link = 'public/storage';
    
    if (symlink($target, $link)) {
        echo "Storage link created successfully<br>";
    } else {
        echo "Failed to create storage link - you may need to do this manually in cPanel<br>";
    }
}

// Test database connection
try {
    $pdo = new PDO(
        "mysql:host=" . env('DB_HOST') . ";dbname=" . env('DB_DATABASE'),
        env('DB_USERNAME'),
        env('DB_PASSWORD')
    );
    echo "Database connection: SUCCESS<br>";
} catch (PDOException $e) {
    echo "Database connection: FAILED - " . $e->getMessage() . "<br>";
}

// Helper function to read .env values
function env($key) {
    $envFile = file_get_contents('.env');
    $lines = explode("\n", $envFile);
    foreach ($lines as $line) {
        if (strpos($line, $key . '=') === 0) {
            return substr($line, strlen($key) + 1);
        }
    }
    return null;
}

echo "<br><strong>Setup complete! Delete this file after running.</strong>";
?>
```

## 🌐 Step 3: Upload via FTP

### 3.1 FTP Upload Process

**If your domain points to the main directory:**
1. Upload all files from `deployment-package` to your domain's root directory
2. Copy `.htaccess.cpanel` to your root as `.htaccess`
3. Point your domain's document root to the `public` folder in cPanel

**If using a subdirectory:**
1. Create a subdirectory (e.g., `construction-site`)
2. Upload all files to this subdirectory
3. Copy `.htaccess.cpanel` to your domain root as `.htaccess`

### 3.2 File Upload Checklist
- [ ] All PHP files uploaded
- [ ] `vendor` folder uploaded completely
- [ ] `public/build` folder uploaded (compiled assets)
- [ ] `.env` file uploaded with production settings
- [ ] `setup-cpanel.php` uploaded
- [ ] `.htaccess.cpanel` renamed to `.htaccess` in correct location

### 3.3 Critical Folders to Verify
- [ ] `vendor/` - Complete composer dependencies
- [ ] `public/build/` - Compiled CSS/JS assets
- [ ] `storage/` - Empty but with proper structure
- [ ] `bootstrap/cache/` - Empty directory

## ⚙️ Step 4: cPanel Configuration

### 4.1 Database Setup
1. **Import Database Structure** (if needed):
   ```sql
   -- Export your local database structure:
   php artisan schema:dump
   ```
   Upload the generated SQL file via phpMyAdmin in cPanel

2. **Run Migrations** via phpMyAdmin:
   - Access phpMyAdmin in cPanel
   - Import the migration SQL or run individual migration queries

### 4.2 File Manager Setup
1. **Set File Permissions** in cPanel File Manager:
   - Select `storage` folder → Permissions → 755
   - Select `bootstrap/cache` → Permissions → 755
   - Select all subdirectories in `storage` → 755

2. **Create Storage Link**:
   - In File Manager, create symbolic link from `public/storage` to `storage/app/public`
   - Or use the setup script (next step)

### 4.3 Run Setup Script
1. Visit `https://yourdomain.com/setup-cpanel.php` in your browser
2. Check all setup results
3. **Delete `setup-cpanel.php` after successful setup**

## 🔧 Step 5: Manual Optimization (cPanel Tools)

### 5.1 Create Optimized Config Files
Create these files manually via cPanel File Manager:

**bootstrap/cache/config.php**:
```php
<?php return [
    // Minimal config cache - create this via File Manager
    'app' => ['name' => 'Construction Company', 'env' => 'production'],
];
```

### 5.2 Application Testing
1. **Test Homepage**: Visit your domain
2. **Test Admin Panel**: Visit `yourdomain.com/admin`
3. **Test Contact Forms**: Submit a test contact form
4. **Check Images**: Verify image uploads work

## 📱 Step 6: Mobile & Desktop Testing

### Test on Multiple Devices:
- [ ] Mobile phones (iOS/Android)
- [ ] Tablets
- [ ] Desktop browsers
- [ ] Contact forms submission
- [ ] Image galleries
- [ ] Navigation menus

## 🔍 Troubleshooting FTP Deployment

### Common Issues & Solutions

**500 Internal Server Error**
1. Check `.htaccess` file is correctly placed
2. Verify all files uploaded completely
3. Check file permissions in cPanel File Manager
4. Review cPanel Error Logs

**Database Connection Error**
1. Verify database credentials in `.env`
2. Ensure database exists in cPanel
3. Check database user permissions
4. Test connection via phpMyAdmin

**Missing CSS/JS Styles**
1. Verify `public/build` folder uploaded
2. Check `vite.config.js` paths
3. Ensure `npm run build` was run locally

**File Upload Issues**
1. Create storage link manually in File Manager
2. Set storage folder permissions to 755
3. Check upload size limits in cPanel

**Admin Panel Not Working**
1. Verify `vendor` folder uploaded completely
2. Check `.env` APP_KEY is set
3. Clear any cached config files

### Emergency Recovery
1. **Re-upload vendor folder** if Composer errors occur
2. **Check Error Logs** in cPanel for specific issues
3. **Verify .env file** uploaded correctly
4. **Test database connection** via phpMyAdmin

## 📞 Support Resources

### Pre-Deployment Checklist
- [ ] Local build completed successfully
- [ ] Production .env configured
- [ ] Database created in cPanel
- [ ] FTP credentials working
- [ ] All files prepared for upload

### Post-Deployment Verification
- [ ] Website loads without errors
- [ ] Admin panel accessible
- [ ] Contact forms working
- [ ] Images displaying correctly
- [ ] Mobile responsiveness verified
- [ ] SSL certificate active

---

**Remember**: Always backup your hosting account before deployment and test everything thoroughly after upload!
