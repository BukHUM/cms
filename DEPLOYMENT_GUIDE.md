# 🚀 Production Deployment Guide

## 📋 Overview
This guide helps you deploy the Laravel CMS backend to production on Ubuntu/Debian servers with PHP 8.2.

## 🔧 Prerequisites

### System Requirements
- **PHP**: 8.2+ (tested with PHP 8.2.28)
- **Composer**: Latest version
- **Node.js**: 18+ 
- **npm**: 8+
- **Web Server**: Apache/Nginx
- **Database**: MySQL/PostgreSQL/SQLite

### Required PHP Extensions
- exif
- mbstring
- xml
- bcmath
- fileinfo
- ctype
- json
- tokenizer
- openssl
- pdo
- curl
- gd
- zip

## 🛠️ Installation Steps

### Step 1: Install PHP Extensions
```bash
# Make the script executable
chmod +x install-php-extensions.sh

# Run the installation script
sudo ./install-php-extensions.sh
```

### Step 2: Deploy Application
```bash
# Make the deployment script executable
chmod +x deploy-production.sh

# Run the deployment script
./deploy-production.sh
```

## 🔍 Troubleshooting

### Common Issues

#### 1. PHP Extension Missing
**Error**: `ext-exif * -> it is missing from your system`

**Solution**:
```bash
# Install missing extensions
sudo apt-get install php8.2-exif php8.2-mbstring php8.2-xml php8.2-bcmath

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

#### 2. PHP Version Compatibility
**Error**: `maennchen/zipstream-php 3.2.0 requires php-64bit ^8.3`

**Solution**: The deployment script automatically uses `--ignore-platform-reqs` to handle this.

#### 3. Composer Dependencies Failed
**Error**: `Your lock file does not contain a compatible set of packages`

**Solution**:
```bash
# Clear composer cache and reinstall
composer clear-cache
composer install --ignore-platform-reqs
```

#### 4. Laravel Commands Failed
**Error**: `Failed opening required 'vendor/autoload.php'`

**Solution**: This happens when composer install fails. Check PHP extensions and try:
```bash
composer install --ignore-platform-reqs --no-dev --optimize-autoloader
```

### Manual Installation Commands

If the scripts fail, you can run these commands manually:

```bash
# 1. Install PHP extensions
sudo apt-get update
sudo apt-get install php8.2-exif php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-fileinfo php8.2-ctype php8.2-json php8.2-tokenizer php8.2-openssl php8.2-pdo php8.2-curl php8.2-gd php8.2-zip php8.2-intl php8.2-imagick

# 2. Install Composer dependencies
composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 3. Install Node.js dependencies
npm install

# 4. Build frontend assets
npm run build

# 5. Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 6. Create storage link
php artisan storage:link

# 7. Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔧 Environment Variables

Make sure your `.env` file is configured for production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Cache and Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

## 📊 Verification

After deployment, verify everything is working:

1. **Check PHP Extensions**:
   ```bash
   php -m | grep -E "(exif|mbstring|xml|bcmath)"
   ```

2. **Check Laravel**:
   ```bash
   php artisan --version
   ```

3. **Check Frontend Assets**:
   ```bash
   ls -la public/build/
   ```

4. **Test Web Access**:
   - Visit your domain
   - Check for any PHP errors in logs

## 🚨 Important Notes

- **PHP 8.2 Compatibility**: The script uses `--ignore-platform-reqs` to handle packages requiring PHP 8.3
- **Root User**: Avoid running composer as root in production
- **Permissions**: Ensure proper file permissions for storage and cache directories
- **Security**: Update your `.env` file with production values
- **Backup**: Always backup your database before deployment

## 📞 Support

If you encounter issues:

1. Check PHP error logs: `/var/log/php8.2-fpm.log`
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify PHP extensions: `php -m`
4. Test composer: `composer diagnose`

## 🔄 Updates

To update the application:

```bash
# Pull latest changes
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader --ignore-platform-reqs
npm install && npm run build

# Clear caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
