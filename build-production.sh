#!/bin/bash

# Production Build Script
# Run this locally before uploading to cPanel

echo "🏗️ Building production assets..."

# Install npm dependencies
echo "📦 Installing npm dependencies..."
npm ci

# Build production assets
echo "⚡ Building Vite assets for production..."
npm run build

# Show build information
echo ""
echo "✅ Production build completed!"
echo ""
echo "📁 Built files are in public/build/ directory"
echo "🚀 Ready to upload to cPanel!"
echo ""
echo "📋 Next steps:"
echo "   1. Upload all files to your cPanel file manager"
echo "   2. Point your domain to the 'public' directory"
echo "   3. Run the deploy-cpanel.sh script on the server"
echo "   4. Configure your .env file"
echo ""
