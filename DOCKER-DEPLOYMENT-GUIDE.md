# Docker Deployment Guide

A comprehensive Docker deployment system for the Construction Company website with support for both development and production environments.

## 🐳 **Quick Start**

### **Development Environment**
```bash
# Start development environment
./docker-deploy.sh dev

# Access the application
open http://localhost:8000
```

### **Production Environment**
```bash
# Start production environment
./docker-deploy.sh prod

# Access the application
open http://localhost:8080
```

## 📋 **Prerequisites**

- **Docker** 20.10+ installed and running
- **Docker Compose** v2.0+ (or docker-compose v1.29+)
- **8GB+ RAM** recommended for all services
- **Ports available**: 8000, 8080, 3306, 6379, 1025, 8025

## 🏗️ **Architecture Overview**

### **Production Stack**
- **App Container**: Laravel + Apache + PHP 8.4 (port 8080)
- **MySQL**: Database with persistent storage (port 3307)
- **Redis**: Caching and session storage (port 6380)
- **Nginx**: Reverse proxy and load balancer (port 8081)
- **Queue Worker**: Background job processing
- **Scheduler**: Laravel cron jobs

### **Development Stack**
- **App Container**: Laravel development server (port 8000)
- **Vite Server**: Hot module replacement for assets (port 5173)
- **MySQL**: Database with development data (port 3308)
- **Redis**: Caching (port 6381)
- **MailHog**: Email testing interface (ports 1025, 8025)

## 🚀 **Deployment Commands**

### **Basic Operations**
```bash
# Development environment
./docker-deploy.sh dev

# Production environment  
./docker-deploy.sh prod

# Stop all containers
./docker-deploy.sh stop

# Restart environment
./docker-deploy.sh restart [dev]

# View logs
./docker-deploy.sh logs [dev]

# Enter container shell
./docker-deploy.sh shell [dev]
```

### **Laravel Commands**
```bash
# Run artisan commands
./docker-deploy.sh artisan dev migrate
./docker-deploy.sh artisan prod cache:clear
./docker-deploy.sh artisan dev db:seed

# Examples
./docker-deploy.sh artisan dev make:model Project
./docker-deploy.sh artisan prod optimize
```

### **Cleanup Operations**
```bash
# Remove containers and images
./docker-deploy.sh cleanup

# Manual cleanup
docker system prune -a
docker volume prune
```

## ⚙️ **Environment Configuration**

### **Development (.env.docker.dev)**
- **Database**: `construction_dev`
- **Debug**: Enabled
- **Cache**: Array (no persistence)
- **Mail**: MailHog for testing
- **Assets**: Vite dev server with HMR

### **Production (.env.docker)**
- **Database**: `construction_db`
- **Debug**: Disabled
- **Cache**: Redis with persistence
- **Mail**: SMTP configuration required
- **Assets**: Compiled and optimized

### **Custom Environment**
```bash
# Copy and modify environment files
cp .env.docker .env.docker.local
# Edit .env.docker.local with your settings
```

## 🗄️ **Database Management**

### **Migrations and Seeds**
```bash
# Run migrations
./docker-deploy.sh artisan dev migrate

# Run seeders
./docker-deploy.sh artisan dev db:seed

# Fresh database
./docker-deploy.sh artisan dev migrate:fresh --seed
```

### **Database Access**
```bash
# Access MySQL directly
docker exec -it construction_mysql_dev mysql -u construction_user -p

# Or use phpMyAdmin (add to docker-compose if needed)
# Credentials: construction_user / construction_password
```

### **Data Persistence**
- **Development**: Volume `mysql_dev_data`
- **Production**: Volume `mysql_data`
- **Backup**: `docker run --rm -v mysql_data:/data -v $(pwd):/backup alpine tar czf /backup/mysql-backup.tar.gz -C /data .`

## 🔧 **Service Details**

### **Application Container**
- **Base**: PHP 8.4 Apache
- **Extensions**: MySQL PDO, GD, ZIP, BCMath
- **Features**: Composer, Node.js, Supervisor
- **Ports**: 8000 (dev), 8080 (prod)

### **MySQL Container**
- **Version**: MySQL 8.0
- **Storage**: Persistent volumes
- **Init Scripts**: Character set and user setup
- **Port**: 3306

### **Redis Container**
- **Version**: Redis 7 Alpine
- **Usage**: Cache, sessions, queues
- **Port**: 6379

### **Nginx Container** (Production)
- **Configuration**: Reverse proxy to app
- **Features**: Gzip, security headers, SSL ready
- **Ports**: 80, 443

## 📊 **Monitoring and Logs**

### **Log Access**
```bash
# All services
./docker-deploy.sh logs

# Specific service
docker logs construction_app -f
docker logs construction_mysql -f

# Laravel logs
docker exec construction_app tail -f storage/logs/laravel.log
```

### **Service Health**
```bash
# Check container status
docker ps

# Check service health
docker exec construction_app php artisan about
docker exec construction_mysql mysqladmin ping
```

## 🔐 **Security Considerations**

### **Environment Variables**
- Change default database passwords
- Set secure APP_KEY
- Configure proper mail credentials
- Use HTTPS in production

### **Network Security**
- Containers isolated in bridge network
- Only necessary ports exposed
- Database not exposed to host in production

### **File Permissions**
- Storage directories properly writable
- Code directories read-only for web server
- Supervisor manages service permissions

## 🚀 **Production Deployment**

### **Server Requirements**
- **OS**: Linux (Ubuntu 20.04+ recommended)
- **RAM**: 4GB minimum, 8GB recommended
- **Storage**: 20GB+ available space
- **Docker**: Latest stable version

### **Deployment Steps**
1. **Clone repository** on production server
2. **Configure environment**: Update `.env.docker`
3. **Deploy**: `./docker-deploy.sh prod`
4. **Setup SSL**: Configure nginx with certificates
5. **Configure DNS**: Point domain to server
6. **Setup backups**: Database and storage volumes

### **SSL Configuration**
```bash
# Place SSL certificates in docker/ssl/
mkdir -p docker/ssl
cp fullchain.pem docker/ssl/
cp privkey.pem docker/ssl/

# Uncomment HTTPS section in nginx config
# Update docker-compose.yml with SSL volume mount
```

### **Domain Configuration**
```bash
# Update nginx configuration
# Edit docker/nginx/sites/default.conf
# Replace yourdomain.com with actual domain
```

## 📈 **Scaling and Performance**

### **Horizontal Scaling**
```bash
# Scale queue workers
docker-compose up -d --scale queue=3

# Scale application (with load balancer)
docker-compose up -d --scale app=3
```

### **Resource Limits**
```yaml
# Add to docker-compose.yml services
deploy:
  resources:
    limits:
      memory: 512M
      cpus: '0.5'
```

### **Performance Optimization**
- **Redis**: Enable for cache and sessions
- **Opcache**: Enabled in production PHP
- **Composer**: Optimized autoloader
- **Assets**: Compiled and minified

## 🔄 **Backup and Recovery**

### **Database Backup**
```bash
# Create backup
docker exec construction_mysql mysqldump -u construction_user -p construction_db > backup.sql

# Restore backup
docker exec -i construction_mysql mysql -u construction_user -p construction_db < backup.sql
```

### **Volume Backup**
```bash
# Backup volumes
docker run --rm -v mysql_data:/data -v $(pwd):/backup alpine tar czf /backup/mysql-backup.tar.gz -C /data .

# Restore volumes
docker run --rm -v mysql_data:/data -v $(pwd):/backup alpine tar xzf /backup/mysql-backup.tar.gz -C /data
```

### **Complete System Backup**
```bash
# Backup configuration and data
tar -czf construction-backup.tar.gz .env.docker docker-compose.yml storage/
```

## 🐛 **Troubleshooting**

### **Common Issues**

**Container won't start**
```bash
# Check logs
docker logs construction_app
# Check resources
docker system df
```

**Database connection error**
```bash
# Check MySQL is running
docker exec construction_mysql mysqladmin ping
# Verify credentials in .env
```

**Permission errors**
```bash
# Fix storage permissions
docker exec construction_app chown -R www-data:www-data storage/
docker exec construction_app chmod -R 775 storage/
```

**Memory issues**
```bash
# Check memory usage
docker stats
# Increase Docker memory limit
```

### **Reset Environment**
```bash
# Complete reset
./docker-deploy.sh stop
./docker-deploy.sh cleanup
./docker-deploy.sh dev  # or prod
```

## 📞 **Support**

### **Useful Commands**
```bash
# Quick health check
./docker-deploy.sh artisan about

# Clear all caches
./docker-deploy.sh artisan cache:clear
./docker-deploy.sh artisan config:clear
./docker-deploy.sh artisan view:clear

# Run tests
./docker-deploy.sh artisan test
```

### **Development Tools**
- **MailHog**: http://localhost:8025 (email testing)
- **Vite Dev Server**: http://localhost:5173 (asset compilation)
- **Redis CLI**: `docker exec -it construction_redis redis-cli`
- **MySQL CLI**: `docker exec -it construction_mysql mysql -u construction_user -p`

---

**Docker deployment system ready! 🚀**

The construction website now supports both development and production Docker environments with comprehensive tooling and documentation.
