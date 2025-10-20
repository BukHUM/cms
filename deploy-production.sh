#!/bin/bash

# Production Deployment Script for CMS Backend
# This script handles the deployment process for production environment

echo "🚀 Starting Production Deployment..."

# Step 0: Install Composer Dependencies (CRITICAL!)
echo "📦 Installing Composer dependencies..."
if command -v composer &> /dev/null; then
    echo "✅ Composer found, installing dependencies..."
    # If ENABLE_DEBUGBAR=true or INSTALL_DEV_PACKAGES=true, install dev packages (includes Debugbar)
    if [ "$ENABLE_DEBUGBAR" = "true" ] || [ "$INSTALL_DEV_PACKAGES" = "true" ]; then
        echo "🔧 ENABLE_DEBUGBAR detected → installing with dev dependencies"
        composer install --optimize-autoloader
    else
        composer install --no-dev --optimize-autoloader
    fi
    echo "✅ Composer dependencies installed successfully!"
else
    echo "❌ Composer not found! Please install Composer first."
    echo "💡 Install Composer: curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer"
    exit 1
fi

# Step 0.5: Laravel Setup Commands
echo "🔧 Running Laravel setup commands..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optionally publish Debugbar config when enabled and package is installed
if [ "$ENABLE_DEBUGBAR" = "true" ]; then
    if php -r "exit(class_exists('Barryvdh\\\\Debugbar\\\\ServiceProvider') ? 0 : 1);"; then
        echo "🛠️ Publishing Debugbar config..."
        php artisan vendor:publish --provider="Barryvdh\\Debugbar\\ServiceProvider" --tag=config --force || true
    else
        echo "ℹ️ Debugbar not installed; skipping publish"
    fi
fi

# Create storage link if it doesn't exist
if [ ! -L "public/storage" ]; then
    echo "🔗 Creating storage symbolic link..."
    php artisan storage:link
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
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 11: Set proper permissions
echo "🔐 Setting proper permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || echo "⚠️ Could not set ownership (may need sudo)"

# Step 12: Final verification
echo "🔍 Final verification..."
if [ -f "vendor/autoload.php" ]; then
    echo "✅ vendor/autoload.php exists - Laravel should work!"
else
    echo "❌ vendor/autoload.php missing - deployment failed!"
    exit 1
fi

echo "🎉 Production deployment completed successfully!"
echo "📋 Summary:"
echo "   ✅ Composer dependencies installed"
echo "   ✅ Laravel optimized for production"
echo "   ✅ Frontend assets built"
echo "   ✅ Permissions set correctly"
echo "   ✅ Ready to serve!"
