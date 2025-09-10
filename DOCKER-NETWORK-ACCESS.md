# Docker Network Access Guide

## 🔌 **Port Configuration**

### **Development Environment Ports**
- **Laravel App**: `http://localhost:8000`
- **Vite Dev Server**: `http://localhost:5173` 
- **MySQL Database**: `localhost:3308`
- **Redis Cache**: `localhost:6381`
- **MailHog Web UI**: `http://localhost:8025`
- **MailHog SMTP**: `localhost:1025`

### **Production Environment Ports**
- **Laravel App**: `http://localhost:8080`
- **Nginx Proxy**: `http://localhost:8081`
- **MySQL Database**: `localhost:3307`
- **Redis Cache**: `localhost:6380`

## 🌐 **Network Access Commands**

### **Start Docker Services**
```bash
# Development environment
./docker-deploy.sh dev

# Production environment  
./docker-deploy.sh prod

# Check running containers
docker ps
```

### **Database Access Commands**

#### **MySQL Access**
```bash
# Development database (port 3308)
mysql -h localhost -P 3308 -u construction_user -p construction_dev

# Production database (port 3307)
mysql -h localhost -P 3307 -u construction_user -p construction_db

# Using Docker exec (direct container access)
docker exec -it construction_mysql_dev mysql -u construction_user -p
docker exec -it construction_mysql mysql -u construction_user -p

# Database credentials
# Username: construction_user
# Password: construction_password
# Dev DB: construction_dev
# Prod DB: construction_db
```

#### **Redis Access**
```bash
# Development Redis (port 6381)
redis-cli -h localhost -p 6381

# Production Redis (port 6380)  
redis-cli -h localhost -p 6380

# Using Docker exec (direct container access)
docker exec -it construction_redis_dev redis-cli
docker exec -it construction_redis redis-cli
```

### **Application Access**

#### **Web Access**
```bash
# Development
open http://localhost:8000
open http://localhost:8000/admin

# Production
open http://localhost:8080
open http://localhost:8080/admin
open http://localhost:8081      # Nginx proxy
```

#### **Email Testing (Development)**
```bash
# MailHog Web Interface
open http://localhost:8025

# SMTP settings for development
# Host: localhost
# Port: 1025
# No authentication required
```

## 🔗 **WLAN/Network Access Setup**

### **Find Your Local IP Address**
```bash
# macOS/Linux
ifconfig | grep "inet " | grep -v 127.0.0.1

# Or use
hostname -I

# Example output: 192.168.1.100
```

### **Access from Other Devices on Same Network**

#### **Update Docker Compose for Network Access**
```bash
# Edit docker-compose.yml and docker-compose.dev.yml
# Change ports from "localhost:port" to "0.0.0.0:port"

# For example, change:
ports:
  - "8000:8000"
# To:
ports:
  - "0.0.0.0:8000:8000"
```

#### **Network Access URLs (replace with your IP)**
```bash
# Development (replace 192.168.1.100 with your IP)
http://192.168.1.100:8000        # Laravel app
http://192.168.1.100:8025        # MailHog
http://192.168.1.100:5173        # Vite dev server

# Production
http://192.168.1.100:8080        # Laravel app
http://192.168.1.100:8081        # Nginx proxy

# Database connections from network
mysql -h 192.168.1.100 -P 3308 -u construction_user -p
redis-cli -h 192.168.1.100 -p 6381
```

### **Firewall Configuration**

#### **macOS Firewall**
```bash
# Allow incoming connections (if firewall is enabled)
sudo /usr/libexec/ApplicationFirewall/socketfilterfw --add /usr/bin/docker
sudo /usr/libexec/ApplicationFirewall/socketfilterfw --unblock /usr/bin/docker

# Or temporarily disable firewall
sudo /usr/libexec/ApplicationFirewall/socketfilterfw --setglobalstate off
```

#### **Linux (Ubuntu/Debian) Firewall**
```bash
# Using ufw
sudo ufw allow 8000/tcp          # Laravel dev
sudo ufw allow 8080/tcp          # Laravel prod  
sudo ufw allow 8081/tcp          # Nginx
sudo ufw allow 3307/tcp          # MySQL prod
sudo ufw allow 3308/tcp          # MySQL dev
sudo ufw allow 6380/tcp          # Redis prod
sudo ufw allow 6381/tcp          # Redis dev
sudo ufw allow 8025/tcp          # MailHog

# Or using iptables
sudo iptables -A INPUT -p tcp --dport 8000 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 8080 -j ACCEPT
sudo iptables -A INPUT -p tcp --dport 8081 -j ACCEPT
```

#### **Windows Firewall**
```bash
# Add firewall rules (run as Administrator)
netsh advfirewall firewall add rule name="Docker Laravel Dev" dir=in action=allow protocol=TCP localport=8000
netsh advfirewall firewall add rule name="Docker Laravel Prod" dir=in action=allow protocol=TCP localport=8080
netsh advfirewall firewall add rule name="Docker Nginx" dir=in action=allow protocol=TCP localport=8081
netsh advfirewall firewall add rule name="Docker MySQL Dev" dir=in action=allow protocol=TCP localport=3308
netsh advfirewall firewall add rule name="Docker MySQL Prod" dir=in action=allow protocol=TCP localport=3307
```

## 🔧 **Docker Compose Network Configuration**

### **Enable Network Access (Edit docker-compose files)**
```yaml
# Update docker-compose.dev.yml for development
services:
  app:
    ports:
      - "0.0.0.0:8000:8000"  # Allow external access
  
  vite:
    ports:
      - "0.0.0.0:5173:5173"
  
  mysql:
    ports:
      - "0.0.0.0:3308:3306"
  
  redis:
    ports:
      - "0.0.0.0:6381:6379"
  
  mailhog:
    ports:
      - "0.0.0.0:1025:1025"
      - "0.0.0.0:8025:8025"
```

```yaml
# Update docker-compose.yml for production
services:
  app:
    ports:
      - "0.0.0.0:8080:80"
  
  mysql:
    ports:
      - "0.0.0.0:3307:3306"
  
  redis:
    ports:
      - "0.0.0.0:6380:6379"
  
  nginx:
    ports:
      - "0.0.0.0:8081:80"
      - "0.0.0.0:8443:443"
```

## 📱 **Mobile Device Testing**

### **Steps for Mobile Testing**
1. **Find your computer's IP address**
2. **Update Docker Compose files** to use `0.0.0.0` instead of `localhost`
3. **Restart Docker services**
4. **Configure firewall** to allow incoming connections
5. **Access from mobile browser**: `http://YOUR_IP:8000`

### **Quick Setup Commands**
```bash
# Stop current services
./docker-deploy.sh stop

# Edit docker-compose files to use 0.0.0.0
# (See configuration examples above)

# Restart services
./docker-deploy.sh dev    # or prod

# Test access
curl http://YOUR_IP:8000  # Replace YOUR_IP with actual IP
```

## 🐛 **Troubleshooting Network Access**

### **Common Issues**

**Port Already in Use**
```bash
# Check what's using the port
lsof -i :3306              # For MySQL default port
lsof -i :6379              # For Redis default port

# Kill process using port
sudo kill -9 PID_NUMBER
```

**Can't Access from Network**
```bash
# Check Docker is binding to all interfaces
docker port construction_app
docker port construction_mysql_dev

# Should show 0.0.0.0:PORT instead of 127.0.0.1:PORT
```

**Firewall Blocking Connections**
```bash
# Test port accessibility
telnet YOUR_IP 8000

# Check firewall status
# macOS
sudo /usr/libexec/ApplicationFirewall/socketfilterfw --getglobalstate

# Linux
sudo ufw status
```

### **Verification Commands**
```bash
# Check all container ports
docker ps --format "table {{.Names}}\t{{.Ports}}"

# Test local connectivity
curl http://localhost:8000
curl http://localhost:8080

# Test network connectivity (replace IP)
curl http://192.168.1.100:8000
```

## 📞 **Quick Reference**

### **Default Development Setup**
```bash
# Start development
./docker-deploy.sh dev

# Access points
http://localhost:8000          # App
http://localhost:8025          # MailHog  
mysql -h localhost -P 3308     # Database
redis-cli -h localhost -p 6381 # Redis
```

### **Network Access Setup**
```bash
# 1. Edit docker-compose files (change localhost to 0.0.0.0)
# 2. Configure firewall
# 3. Restart services
./docker-deploy.sh restart dev

# 4. Access from network
http://YOUR_IP:8000
```

---

**All ports are now configured to avoid conflicts and enable network access! 🌐**
