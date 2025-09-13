# Installation Guide

This guide will help you install and set up your Laravel application via cPanel or any web hosting environment.

## Quick Start

1. Upload all project files to your hosting account
2. Visit your domain URL in a web browser
3. Follow the installation wizard

## System Requirements

### PHP Requirements
- **PHP Version**: 8.1 or higher
- **Memory Limit**: 128MB or higher (256MB recommended)
- **Max Execution Time**: 300 seconds or higher

### Required PHP Extensions
- `mbstring` - **Critical** (causes `mb_split()` errors if missing)
- `openssl` - For encryption and security
- `pdo` - Database connectivity
- `tokenizer` - Laravel framework requirement
- `xml` - XML processing
- `ctype` - Character type checking
- `json` - JSON processing
- `bcmath` - Arbitrary precision mathematics
- `fileinfo` - File information
- `gd` - Image processing (recommended)
- `curl` - HTTP requests (recommended)

### Directory Permissions
- `storage/` - Must be writable (755 or 775)
- `bootstrap/cache/` - Must be writable (755 or 775)

## Installation Steps

### Step 1: Upload Files
1. Download/extract the project files
2. Upload all files to your hosting account's public_html directory (or subdirectory)
3. Ensure the `.env` file is uploaded (may be hidden)

### Step 2: Access Installation Wizard
1. Visit your domain in a web browser
2. You'll be automatically redirected to the installation wizard
3. Follow the step-by-step process

### Step 3: System Requirements Check
- The wizard will check all system requirements
- Fix any failed requirements before proceeding
- Contact your hosting provider if PHP extensions are missing

### Step 4: Database Configuration
1. Choose your database type (MySQL, PostgreSQL, or SQLite)
2. Enter database connection details
3. Configure application settings (name, URL, environment)
4. Test the connection before proceeding

### Step 5: Admin Account Creation
1. Create your administrator account
2. Provide personal and contact information
3. Set a strong password

### Step 6: Installation Completion
- The system will run database migrations
- Generate application keys
- Create necessary storage links
- Display success confirmation

## Troubleshooting

### Common Issues

#### 1. "Call to undefined function Illuminate\Support\mb_split()" Error
**Cause**: Missing `mbstring` PHP extension

**Solutions**:
- **cPanel**: Go to PHP Selector → Extensions → Enable `mbstring`
- **VPS/Dedicated**: Install via package manager
  ```bash
  # Ubuntu/Debian
  sudo apt-get install php-mbstring
  
  # CentOS/RHEL
  sudo yum install php-mbstring
  ```
- **Contact hosting provider** if you can't enable extensions

#### 2. Database Connection Failed
**Possible Causes**:
- Incorrect database credentials
- Database server not running
- Network connectivity issues
- Database doesn't exist

**Solutions**:
- Verify credentials in cPanel → MySQL Databases
- Ensure database exists and user has proper permissions
- Check if remote connections are allowed (if using external DB)

#### 3. Permission Denied Errors
**Cause**: Incorrect file/directory permissions

**Solution**:
```bash
chmod -R 755 /path/to/your/project
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 4. Memory Limit Exceeded
**Cause**: PHP memory limit too low

**Solutions**:
- **cPanel**: PHP Selector → Options → Increase `memory_limit`
- **php.ini**: Set `memory_limit = 256M`
- Contact hosting provider for assistance

#### 5. Installation Wizard Not Appearing
**Possible Causes**:
- Installation already completed
- Middleware not properly configured
- Route conflicts

**Solutions**:
- Delete `storage/app/installed.lock` to restart installation
- Check `.htaccess` file exists and is properly configured
- Verify all files were uploaded correctly

### Getting Help

If you encounter issues not covered here:

1. **Check Error Logs**: Look in cPanel → Error Logs for detailed error messages
2. **Contact Support**: Reach out to your hosting provider's technical support
3. **Documentation**: Refer to Laravel's official documentation
4. **Community**: Ask questions on Laravel forums or Stack Overflow

## Post-Installation

### Security Recommendations
1. **Change Default Passwords**: Update any default credentials
2. **Enable HTTPS**: Configure SSL certificate for secure connections
3. **Regular Updates**: Keep the application and dependencies updated
4. **Backup Strategy**: Implement regular database and file backups

### Performance Optimization
1. **Enable Caching**: Configure Redis or Memcached if available
2. **Optimize Images**: Use appropriate image formats and sizes
3. **CDN Setup**: Consider using a Content Delivery Network
4. **Database Indexing**: Optimize database queries and indexes

## File Structure

After installation, your directory structure should look like:

```
your-domain/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── .htaccess
├── artisan
├── composer.json
└── ...
```

## Environment Configuration

The `.env` file contains important configuration:

```env
APP_NAME="Your App Name"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

**Important**: Never share your `.env` file or commit it to version control.

## Support

For additional support:
- Check the troubleshooting section above
- Review server error logs
- Contact your hosting provider
- Consult Laravel documentation

---

**Note**: This installation process is designed to be user-friendly and handle most common scenarios automatically. If you encounter any issues, the built-in error handling will guide you through the resolution process.