# Construction Company Website

A modern, responsive construction company website built with Laravel 11, Filament 4, and Tailwind CSS. Features a comprehensive admin panel, mobile-native design, and optimized for both desktop and mobile experiences.

![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)
![Filament](https://img.shields.io/badge/Filament-4.x-orange.svg)
![PHP](https://img.shields.io/badge/PHP-8.4+-blue.svg)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-3.x-blue.svg)

## 🚀 Features

### Frontend Features
- **Responsive Design**: Mobile-native experience with tablet and desktop optimization
- **Modern UI**: Glass morphism effects, smooth animations, and professional design
- **Performance Optimized**: Fast loading with optimized assets and caching
- **SEO Friendly**: Proper meta tags, structured data, and semantic HTML
- **Accessibility**: WCAG compliant with proper ARIA labels and keyboard navigation

### Admin Panel (Filament)
- **Complete CMS**: Manage all website content through an intuitive admin interface
- **User Management**: Role-based access control and user administration
- **Content Management**: Services, projects, team members, news, and more
- **Media Management**: Image upload, optimization, and gallery management
- **Form Management**: Contact forms and trade partner prequalifications
- **Analytics**: Basic analytics and form submission tracking

### Key Pages
- **Home**: Hero section with company overview and latest projects
- **Services**: Detailed service listings with descriptions and features
- **Projects**: Project showcase with filtering and detailed views
- **Gallery**: Image gallery with categories and lightbox viewing
- **Safety**: Safety records, statistics, and certifications
- **News/Blog**: Company news and industry updates
- **Partners**: Trade partner directory and prequalification system
- **Contact**: Contact forms with location and company information

## 🛠️ Tech Stack

- **Backend**: Laravel 11.x
- **Admin Panel**: Filament 4.x
- **Frontend**: Blade Templates + Alpine.js
- **Styling**: Tailwind CSS 3.x
- **Build Tool**: Vite
- **Database**: SQLite (development) / MySQL (production)
- **Icons**: Heroicons
- **Animations**: AOS (Animate On Scroll)

## 📋 Requirements

- PHP 8.4 or higher
- Composer
- Node.js 18+ and NPM
- MySQL 8.0+ (for production)
- Web server (Apache/Nginx)

## 🏗️ Local Development Setup

### 1. Clone the Repository
```bash
git clone <repository-url>
cd constraction-laravel
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database (SQLite is used by default for development)
touch database/database.sqlite
```

### 4. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed database with sample data (optional)
php artisan db:seed
```

### 5. Storage Setup
```bash
# Create symbolic link for storage
php artisan storage:link
```

### 6. Development Server
```bash
# Start Laravel development server
php artisan serve

# In another terminal, start Vite dev server
npm run dev
```

Visit `http://localhost:8000` to view the website.
Admin panel is available at `http://localhost:8000/admin`.

## 🌐 cPanel Deployment

Choose your deployment method based on your hosting access:

### 🔑 **Option A: SSH Access (Recommended)**
See the SSH deployment section below.

### 📁 **Option B: FTP-Only Access**
If you only have FTP access (no SSH), use our simplified FTP deployment process:

```bash
# Run the FTP preparation script
./prepare-ftp-deployment.sh
```

This will:
- Install all dependencies locally
- Build production assets
- Generate application key
- Create a ready-to-upload package
- Include setup scripts for cPanel

**📖 For detailed FTP instructions, see: `FTP-DEPLOYMENT-GUIDE.md`**

---

### Prerequisites for cPanel Hosting
- PHP 8.4+ enabled
- MySQL database created
- FTP access or SSH access
- Composer access (for SSH) or upload vendor folder (for FTP)

### Step 1: Prepare for Production

#### For SSH Access:
#### Build Production Assets Locally
```bash
# Run the build script
./build-production.sh

# Or manually:
npm ci
npm run build
```

#### For FTP-Only Access:
```bash
# Run the FTP preparation script (includes everything)
./prepare-ftp-deployment.sh
```
This creates a complete deployment package ready for FTP upload.

#### Prepare Environment File (SSH only - FTP script handles this)
1. Copy `.env.cpanel.example` to `.env` and configure:
   ```bash
   cp .env.cpanel.example .env
   ```

2. Update the `.env` file with your production settings:
   ```env
   APP_NAME="Your Construction Company"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   
   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

### Step 2: Upload to cPanel

#### Option A: File Manager Upload
1. **Compress your project** (excluding node_modules and .git):
   ```bash
   tar -czf construction-website.tar.gz --exclude=node_modules --exclude=.git .
   ```

2. **Upload via cPanel File Manager**:
   - Upload the compressed file to your domain's root directory
   - Extract the files

3. **Set Document Root**:
   - In cPanel, set your domain's document root to point to the `public` folder
   - Or copy `.htaccess.cpanel` to your domain root as `.htaccess`

#### Option B: Git Deployment (if available)
```bash
git clone <repository-url> /path/to/your/domain
cd /path/to/your/domain
```

### Step 3: Server Configuration

#### Run Deployment Script
```bash
# Make script executable and run
chmod +x deploy-cpanel.sh
./deploy-cpanel.sh
```

#### Manual Deployment Steps (if script doesn't work)
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Set permissions
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache

# Application setup
php artisan key:generate
php artisan storage:link
php artisan migrate --force

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Step 4: Final Configuration

1. **Environment File**: Ensure `.env` has correct production settings
2. **Database**: Import any required data or run seeders
3. **File Permissions**: Verify storage and cache directories are writable
4. **SSL Certificate**: Configure HTTPS in cPanel
5. **Email**: Configure SMTP settings for contact forms

### Troubleshooting cPanel Deployment

#### Common Issues:

**500 Internal Server Error**
```bash
# Check storage permissions
chmod -R 775 storage bootstrap/cache

# Clear cache
php artisan cache:clear
php artisan config:clear
```

**Database Connection Issues**
- Verify database credentials in `.env`
- Ensure database user has proper permissions
- Check if database server is accessible

**Missing Files**
- Ensure all vendor dependencies are uploaded
- Check if symbolic storage link exists
- Verify .htaccess files are present

## 🔧 Configuration

### Admin Panel Access
- URL: `yoursite.com/admin`
- Default admin user is created during seeding
- Configure in `config/filament.php`

### Email Configuration
Update `.env` with your SMTP settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

### File Upload Settings
Configure in `.env`:
```env
UPLOAD_MAX_SIZE=10240
IMAGE_MAX_SIZE=5120
```

## 🎨 Customization

### Branding
- Update colors in `resources/css/app.css`
- Modify logo and favicon in `public/` directory
- Configure site settings through admin panel

### Content
- All content is manageable through the Filament admin panel
- Services, projects, team members, and pages are fully editable
- Image galleries and news posts can be added/edited

### Styling
- Tailwind CSS classes can be customized
- Mobile-responsive design adapts to all screen sizes
- Custom CSS can be added to the layout files

## 📁 Project Structure

```
construction-laravel/
├── app/
│   ├── Filament/          # Admin panel resources
│   ├── Http/Controllers/  # Application controllers
│   ├── Models/           # Eloquent models
│   └── Providers/        # Service providers
├── database/
│   ├── migrations/       # Database migrations
│   └── seeders/         # Database seeders
├── public/
│   ├── build/           # Compiled assets (after build)
│   ├── storage/         # Public storage link
│   └── index.php        # Application entry point
├── resources/
│   ├── css/             # Source CSS files
│   ├── js/              # Source JS files
│   └── views/           # Blade templates
├── routes/
│   └── web.php          # Web routes
├── storage/
│   ├── app/             # Application storage
│   └── logs/            # Application logs
├── deploy-cpanel.sh     # cPanel deployment script
├── build-production.sh  # Production build script
└── .env.cpanel.example  # Production environment template
```

## 🚀 Performance Optimization

### Production Optimizations
- Asset compilation and minification via Vite
- Route and config caching
- Database query optimization
- Image optimization and lazy loading
- CDN-ready asset structure

### Caching
```bash
# Enable all caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear all caches
php artisan optimize:clear
```

## 🔒 Security

### Security Features
- CSRF protection on all forms
- XSS protection via Blade templating
- SQL injection prevention via Eloquent ORM
- Security headers configured
- File upload validation and sanitization

### Security Headers (in .htaccess)
- X-Content-Type-Options
- X-Frame-Options
- X-XSS-Protection
- Content Security Policy (configurable)

## 📞 Support

For deployment assistance or customization needs:
- Review the troubleshooting section above
- Check Laravel documentation: https://laravel.com/docs
- Filament documentation: https://filamentphp.com/docs

## 📄 License

This project is proprietary software. All rights reserved.

---

Built with ❤️ using Laravel, Filament, and Tailwind CSS.
