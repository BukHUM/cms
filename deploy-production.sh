#!/bin/bash

# Production Deployment Script for CMS Backend
# This script handles the deployment process for production environment

echo "🚀 Starting Production Deployment..."

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

# Step 3.5: Verify package.json configuration
echo "🔍 Verifying package.json configuration..."
if grep -q '"type": "module"' package.json; then
    echo "✅ package.json is configured for ES modules"
else
    echo "⚠️ package.json may need 'type: module' configuration"
fi

# Step 4: Install Tailwind CSS and PostCSS plugin
echo "🎨 Installing Tailwind CSS and PostCSS plugin..."
npm install -D tailwindcss@latest @tailwindcss/postcss postcss autoprefixer

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
    '@tailwindcss/postcss': {},
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
    echo "🎉 Production deployment completed!"
else
    echo "❌ Build failed!"
    echo "🔍 Please check the error messages above."
    exit 1
fi
