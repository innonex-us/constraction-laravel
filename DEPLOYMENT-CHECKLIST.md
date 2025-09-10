# cPanel Deployment Checklist

## Pre-Deployment Checklist

### Local Environment
- [ ] All features tested locally
- [ ] Production assets built (`./build-production.sh`)
- [ ] Environment file configured (`.env.cpanel.example` → `.env`)
- [ ] Database credentials obtained from hosting provider
- [ ] SMTP email settings configured

### Hosting Requirements Verified
- [ ] PHP 8.4+ enabled on hosting account
- [ ] MySQL database created
- [ ] Database user created with full permissions
- [ ] Composer available (or vendor folder prepared for upload)
- [ ] SSL certificate configured

## Deployment Steps

### 1. File Upload
- [ ] Project files uploaded to hosting account
- [ ] `.htaccess.cpanel` copied to domain root as `.htaccess` (if subdirectory setup)
- [ ] Public folder set as document root OR subdirectory configuration applied

### 2. Server Configuration
- [ ] Deployment script executed (`./deploy-cpanel.sh`)
- [ ] OR manual steps completed:
  - [ ] `composer install --optimize-autoloader --no-dev`
  - [ ] File permissions set (755 for directories, 644 for files)
  - [ ] Storage permissions set (775 for storage and bootstrap/cache)

### 3. Application Setup
- [ ] Application key generated (`php artisan key:generate`)
- [ ] Storage link created (`php artisan storage:link`)
- [ ] Database migrated (`php artisan migrate --force`)
- [ ] Production caches created:
  - [ ] `php artisan config:cache`
  - [ ] `php artisan route:cache`
  - [ ] `php artisan view:cache`

### 4. Configuration Verification
- [ ] `.env` file has correct production settings
- [ ] Database connection working
- [ ] File uploads working (storage directory writable)
- [ ] Email sending working (contact forms)
- [ ] Admin panel accessible (`/admin`)

## Post-Deployment Testing

### Frontend Testing
- [ ] Homepage loads correctly
- [ ] All navigation links work
- [ ] Mobile responsiveness verified
- [ ] Contact forms submit successfully
- [ ] Image galleries display properly
- [ ] Services and projects pages functional

### Admin Panel Testing
- [ ] Admin login working
- [ ] Content management functional
- [ ] File uploads working
- [ ] User management accessible
- [ ] Dashboard displaying correctly

### Performance Testing
- [ ] Page load speeds acceptable
- [ ] Images loading and optimized
- [ ] CSS and JS assets loading
- [ ] No console errors in browser
- [ ] SSL certificate working (HTTPS)

## Troubleshooting Quick Reference

### Common Issues & Solutions

**500 Internal Server Error**
```bash
chmod -R 775 storage bootstrap/cache
php artisan cache:clear
php artisan config:clear
```

**Database Connection Error**
- Verify credentials in `.env`
- Check database user permissions
- Confirm database exists

**Missing CSS/JS Assets**
- Verify `npm run build` was executed
- Check if `public/build` folder exists
- Ensure Vite manifest file present

**File Upload Issues**
- Check storage directory permissions
- Verify storage link exists: `php artisan storage:link`
- Confirm upload size limits in hosting

**Admin Panel Not Accessible**
- Clear route cache: `php artisan route:clear`
- Verify URL: `yourdomain.com/admin`
- Check admin user exists in database

## Emergency Rollback Plan

1. **Backup Current Files**: Always backup before deployment
2. **Database Backup**: Export current database
3. **Rollback Steps**:
   - Restore previous file version
   - Restore database backup
   - Clear all caches
   - Verify functionality

## Maintenance Commands

```bash
# Update application
git pull origin main
composer install --optimize-autoloader --no-dev
php artisan migrate --force
php artisan optimize

# Clear caches if issues occur
php artisan optimize:clear

# View logs for debugging
tail -f storage/logs/laravel.log
```

## Contact Support

If deployment issues persist:
1. Check server error logs in cPanel
2. Review Laravel logs in `storage/logs/`
3. Verify PHP error logs
4. Contact hosting provider for server-specific issues

---

**Remember**: Always test in a staging environment before deploying to production!
