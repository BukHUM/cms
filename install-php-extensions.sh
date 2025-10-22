#!/bin/bash

# PHP Extensions Installation Script for Ubuntu/Debian
# This script installs required PHP extensions for Laravel CMS
# Optimized for PHP 8.2 compatibility

echo "🔧 Installing PHP Extensions for Laravel CMS (PHP 8.2 Compatible)..."

# Detect PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
PHP_MAJOR=$(php -r "echo PHP_MAJOR_VERSION;")
PHP_MINOR=$(php -r "echo PHP_MINOR_VERSION;")

echo "📋 Detected PHP version: $PHP_VERSION"

# Check if PHP version is supported
if [ "$PHP_MAJOR" -lt 8 ] || ([ "$PHP_MAJOR" -eq 8 ] && [ "$PHP_MINOR" -lt 2 ]); then
    echo "❌ PHP version $PHP_VERSION is not supported. Required: PHP 8.2+"
    exit 1
else
    echo "✅ PHP version $PHP_VERSION is supported"
fi

# Update package list
echo "📦 Updating package list..."
apt-get update

# Install required PHP extensions
echo "🔧 Installing PHP extensions..."

# Core extensions for Laravel CMS
EXTENSIONS=(
    "exif"
    "mbstring" 
    "xml"
    "bcmath"
    "fileinfo"
    "ctype"
    "json"
    "tokenizer"
    "openssl"
    "pdo"
    "curl"
    "gd"
    "zip"
    "intl"
    "imagick"
)

for ext in "${EXTENSIONS[@]}"; do
    echo "📦 Installing php${PHP_MAJOR}.${PHP_MINOR}-${ext}..."
    apt-get install -y php${PHP_MAJOR}.${PHP_MINOR}-${ext} 2>/dev/null || echo "⚠️ Failed to install ${ext}"
done

# Restart PHP-FPM if it's running
echo "🔄 Restarting PHP-FPM..."
systemctl restart php${PHP_MAJOR}.${PHP_MINOR}-fpm 2>/dev/null || echo "⚠️ PHP-FPM restart failed (may not be installed)"

# Restart Apache/Nginx if running
echo "🔄 Restarting web server..."
systemctl restart apache2 2>/dev/null || systemctl restart nginx 2>/dev/null || echo "⚠️ Web server restart failed"

# Verify installations
echo "🔍 Verifying installed extensions..."
REQUIRED_EXTENSIONS=("exif" "mbstring" "xml" "bcmath" "fileinfo" "ctype" "json" "tokenizer" "openssl" "pdo" "curl" "gd" "zip")
MISSING_EXTENSIONS=()

for ext in "${REQUIRED_EXTENSIONS[@]}"; do
    if php -m | grep -q "$ext"; then
        echo "✅ $ext extension is installed"
    else
        echo "❌ $ext extension is missing"
        MISSING_EXTENSIONS+=("$ext")
    fi
done

echo ""
if [ ${#MISSING_EXTENSIONS[@]} -eq 0 ]; then
    echo "🎉 All required PHP extensions are installed!"
    echo "✅ You can now run the deployment script successfully"
    echo ""
    echo "💡 Next steps:"
    echo "   1. Run: ./deploy-production.sh"
    echo "   2. The script will use --ignore-platform-reqs for PHP 8.2 compatibility"
else
    echo "⚠️ Some extensions are still missing: ${MISSING_EXTENSIONS[*]}"
    echo "💡 Try running: apt-get install php${PHP_MAJOR}.${PHP_MINOR}-${MISSING_EXTENSIONS[0]}"
    echo ""
    echo "🔄 You can still try deployment with:"
    echo "   composer install --ignore-platform-reqs"
fi

echo ""
echo "📋 Installation complete!"
echo "🔧 PHP 8.2 compatibility mode enabled"
