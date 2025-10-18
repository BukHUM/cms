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

# Step 4: Install Tailwind CSS separately
echo "🎨 Installing Tailwind CSS..."
npm install -D tailwindcss@latest postcss autoprefixer

# Step 5: Create Tailwind config if not exists
if [ ! -f "tailwind.config.cjs" ]; then
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
fi

# Step 6: Create PostCSS config if not exists
if [ ! -f "postcss.config.cjs" ]; then
    echo "⚙️ Creating PostCSS config..."
    cat > postcss.config.cjs << 'EOF'
module.exports = {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
EOF
fi

# Step 7: Build assets
echo "🔨 Building assets..."
npm run build

# Step 8: Check if build was successful
if [ $? -eq 0 ]; then
    echo "✅ Build completed successfully!"
    echo "🎉 Production deployment completed!"
else
    echo "❌ Build failed!"
    echo "🔍 Please check the error messages above."
    exit 1
fi
