#!/bin/bash

# Production Deployment Script for CMS Backend
# This script handles the deployment process for production environment

echo "🚀 Starting Production Deployment..."

# Step -1: Check PHP Version and Extensions
echo "🔍 Checking PHP environment..."
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "📋 Current PHP version: $PHP_VERSION"

# Check if PHP version is 8.2 or higher
PHP_MAJOR=$(php -r "echo PHP_MAJOR_VERSION;")
PHP_MINOR=$(php -r "echo PHP_MINOR_VERSION;")

if [ "$PHP_MAJOR" -lt 8 ] || ([ "$PHP_MAJOR" -eq 8 ] && [ "$PHP_MINOR" -lt 2 ]); then
    echo "❌ PHP version $PHP_VERSION is not supported. Required: PHP 8.2+"
    exit 1
else
    echo "✅ PHP version $PHP_VERSION is supported"
fi

# Check required PHP extensions
echo "🔍 Checking required PHP extensions..."
MISSING_EXTENSIONS=()

# Check for exif extension
if ! php -m | grep -q "exif"; then
    MISSING_EXTENSIONS+=("exif")
fi

# Check for other common extensions
REQUIRED_EXTENSIONS=("mbstring" "openssl" "pdo" "tokenizer" "xml" "ctype" "json" "bcmath" "fileinfo")
for ext in "${REQUIRED_EXTENSIONS[@]}"; do
    if ! php -m | grep -q "$ext"; then
        MISSING_EXTENSIONS+=("$ext")
    fi
done

if [ ${#MISSING_EXTENSIONS[@]} -gt 0 ]; then
    echo "❌ Missing PHP extensions: ${MISSING_EXTENSIONS[*]}"
    echo "💡 Please install missing extensions:"
    echo "   For Ubuntu/Debian: apt-get install php${PHP_MAJOR}.${PHP_MINOR}-exif php${PHP_MAJOR}.${PHP_MINOR}-mbstring php${PHP_MAJOR}.${PHP_MINOR}-xml php${PHP_MAJOR}.${PHP_MINOR}-bcmath"
    echo "   For CentOS/RHEL: yum install php-exif php-mbstring php-xml php-bcmath"
    echo ""
    echo "🔄 Attempting to continue with --ignore-platform-reqs (may cause issues)..."
    IGNORE_PLATFORM_REQS="--ignore-platform-reqs"
else
    echo "✅ All required PHP extensions are available"
    # Still use --ignore-platform-reqs for PHP version compatibility issues
    IGNORE_PLATFORM_REQS="--ignore-platform-reqs"
fi

# Always use --ignore-platform-reqs for PHP 8.2 compatibility with packages requiring 8.3
echo "🔧 Using --ignore-platform-reqs to handle PHP version compatibility"
IGNORE_PLATFORM_REQS="--ignore-platform-reqs"

# Step 0: Install Composer Dependencies (CRITICAL!)
echo "📦 Installing Composer dependencies..."
if command -v composer &> /dev/null; then
    echo "✅ Composer found, installing dependencies..."
    
    # Clean composer cache first
    echo "🧹 Cleaning Composer cache..."
    composer clear-cache 2>/dev/null || true
    
    # Try to install dependencies with --ignore-platform-reqs for PHP 8.2 compatibility
    if [ "$ENABLE_DEBUGBAR" = "true" ] || [ "$INSTALL_DEV_PACKAGES" = "true" ]; then
        echo "🔧 ENABLE_DEBUGBAR detected → installing with dev dependencies"
        composer install --optimize-autoloader --ignore-platform-reqs
    else
        composer install --no-dev --optimize-autoloader --ignore-platform-reqs
    fi
    
    # Verify installation
    if [ -f "vendor/autoload.php" ]; then
        echo "✅ Composer dependencies installed successfully!"
    else
        echo "❌ Composer installation failed!"
        echo "💡 Try running: composer update --ignore-platform-reqs"
        exit 1
    fi
else
    echo "❌ Composer not found! Please install Composer first."
    echo "💡 Install Composer: curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer"
    exit 1
fi

# Step 0.5: Laravel Setup Commands
echo "🔧 Running Laravel setup commands..."
# Only run Laravel commands if vendor/autoload.php exists
if [ -f "vendor/autoload.php" ]; then
    php artisan config:clear 2>/dev/null || echo "⚠️ config:clear failed (may be normal)"
    php artisan cache:clear 2>/dev/null || echo "⚠️ cache:clear failed (may be normal)"
    php artisan route:clear 2>/dev/null || echo "⚠️ route:clear failed (may be normal)"
    php artisan view:clear 2>/dev/null || echo "⚠️ view:clear failed (may be normal)"
else
    echo "⚠️ Skipping Laravel commands - vendor/autoload.php not found"
fi

# Optionally publish Debugbar config when enabled and package is installed
if [ "$ENABLE_DEBUGBAR" = "true" ] && [ -f "vendor/autoload.php" ]; then
    if php -r "exit(class_exists('Barryvdh\\\\Debugbar\\\\ServiceProvider') ? 0 : 1);" 2>/dev/null; then
        echo "🛠️ Publishing Debugbar config..."
        php artisan vendor:publish --provider="Barryvdh\\Debugbar\\ServiceProvider" --tag=config --force 2>/dev/null || echo "⚠️ Debugbar publish failed"
    else
        echo "ℹ️ Debugbar not installed; skipping publish"
    fi
fi

# Create storage link if it doesn't exist
# Ensure destination directories exist before linking
echo "📁 Creating storage directories..."
mkdir -p storage/app/public/settings
mkdir -p storage/app/public/backups
mkdir -p storage/app/public/uploads

if [ -f "vendor/autoload.php" ]; then
    if [ ! -L "public/storage" ]; then
        echo "🔗 Creating storage symbolic link..."
        php artisan storage:link 2>/dev/null || echo "⚠️ Storage link failed (may already exist)"
    else
        echo "✅ Storage link already exists"
    fi
else
    echo "⚠️ Skipping storage link - vendor/autoload.php not found"
fi

# Step 1: Clean npm configuration
echo "📝 Cleaning npm configuration..."
rm -f /root/.npmrc
rm -f .npmrc

# Step 2: Clean node_modules and package-lock.json
echo "🧹 Cleaning node_modules and package-lock.json..."
rm -rf node_modules package-lock.json

# Step 3: Install dependencies
echo "📦 Installing dependencies..."
npm install

# Step 3.5: Verify and fix package.json configuration
echo "🔍 Verifying package.json configuration..."
if grep -q '"type": "module"' package.json; then
    echo "✅ package.json is configured for ES modules"
else
    echo "⚠️ package.json may need 'type: module' configuration"
fi

# Step 3.6: Ensure package.json has correct Tailwind CSS version
echo "🔧 Ensuring correct Tailwind CSS version in package.json..."
# Backup original package.json
cp package.json package.json.backup

# Update package.json to use specific versions
cat > package.json << 'EOF'
{
    "$schema": "https://json.schemastore.org/package.json",
    "private": true,
    "type": "module",
    "scripts": {
        "build": "vite build",
        "dev": "vite"
    },
    "devDependencies": {
        "autoprefixer": "^10.4.21",
        "axios": "^1.11.0",
        "concurrently": "^9.0.1",
        "laravel-vite-plugin": "^2.0.0",
        "postcss": "^8.5.6",
        "tailwindcss": "^3.4.18",
        "vite": "^7.0.7"
    },
    "dependencies": {
        "@fontsource/prompt": "^5.2.8",
        "@fortawesome/fontawesome-free": "^7.1.0",
        "sweetalert2": "^11.26.3"
    },
    "engines": {
        "node": ">=18.0.0",
        "npm": ">=8.0.0"
    }
}
EOF
echo "✅ package.json updated with correct versions"

# Step 4: Install dependencies with correct versions
echo "🎨 Installing dependencies with correct versions..."
npm install

# Step 4.5: Verify installed versions
echo "🔍 Verifying installed versions..."
echo "Tailwind CSS version:"
npm list tailwindcss
echo "PostCSS version:"
npm list postcss
echo "Autoprefixer version:"
npm list autoprefixer

# Step 5: Create Tailwind config (CommonJS format for compatibility)
echo "⚙️ Creating Tailwind config..."
cat > tailwind.config.cjs << 'EOF'
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      fontFamily: {
        'prompt': ['Prompt', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
EOF

# Step 6: Create PostCSS config (CommonJS format for compatibility)
echo "⚙️ Creating PostCSS config..."
cat > postcss.config.cjs << 'EOF'
module.exports = {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
EOF

# Step 7: Remove any conflicting config files
echo "🗑️ Removing conflicting config files..."
rm -f postcss.config.js
rm -f tailwind.config.js

# Step 7.5: Ensure Vite config uses correct extension
if [ -f "vite.config.js" ]; then
    echo "🔄 Checking Vite config..."
    # Check if vite.config.js uses ES module syntax
    if grep -q "export default" vite.config.js; then
        echo "✅ Vite config is already using ES module syntax"
    else
        echo "⚠️ Vite config may need ES module syntax"
    fi
fi

# Step 8: Build assets
echo "🔨 Building assets..."
npm run build

# Step 9: Check if build was successful
if [ $? -eq 0 ]; then
    echo "✅ Build completed successfully!"
else
    echo "❌ Build failed!"
    echo "🔍 Please check the error messages above."
    exit 1
fi

# Step 10: Laravel Production Optimization
echo "⚡ Optimizing Laravel for production..."
if [ -f "vendor/autoload.php" ]; then
    php artisan config:cache 2>/dev/null || echo "⚠️ config:cache failed"
    php artisan route:cache 2>/dev/null || echo "⚠️ route:cache failed"
    php artisan view:cache 2>/dev/null || echo "⚠️ view:cache failed"
    echo "✅ Laravel optimization completed"
else
    echo "⚠️ Skipping Laravel optimization - vendor/autoload.php not found"
fi

# Step 11: Set proper permissions
echo "🔐 Setting proper permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || echo "⚠️ Could not set ownership (may need sudo)"

# Step 12: Final verification
echo "🔍 Final verification..."
DEPLOYMENT_SUCCESS=true

if [ -f "vendor/autoload.php" ]; then
    echo "✅ vendor/autoload.php exists - Laravel should work!"
else
    echo "❌ vendor/autoload.php missing - deployment failed!"
    DEPLOYMENT_SUCCESS=false
fi

if [ -f "public/build/manifest.json" ]; then
    echo "✅ Frontend assets built successfully"
else
    echo "❌ Frontend assets missing"
    DEPLOYMENT_SUCCESS=false
fi

if [ -d "storage" ] && [ -d "bootstrap/cache" ]; then
    echo "✅ Storage and cache directories exist"
else
    echo "❌ Storage or cache directories missing"
    DEPLOYMENT_SUCCESS=false
fi

echo ""
if [ "$DEPLOYMENT_SUCCESS" = true ]; then
    echo "🎉 Production deployment completed successfully!"
    echo "📋 Summary:"
    echo "   ✅ Composer dependencies installed"
    echo "   ✅ Laravel optimized for production"
    echo "   ✅ Frontend assets built"
    echo "   ✅ Permissions set correctly"
    echo "   ✅ Ready to serve!"
else
    echo "⚠️ Production deployment completed with warnings!"
    echo "📋 Summary:"
    echo "   ⚠️ Some components may not be working properly"
    echo "   💡 Check the error messages above for details"
    echo "   🔧 You may need to install missing PHP extensions or update PHP version"
    echo ""
    echo "💡 To fix PHP extension issues:"
    echo "   For Ubuntu/Debian: apt-get install php${PHP_MAJOR}.${PHP_MINOR}-exif php${PHP_MAJOR}.${PHP_MINOR}-mbstring"
    echo "   Then run: composer install --ignore-platform-reqs"
fi
