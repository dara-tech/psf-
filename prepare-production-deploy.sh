#!/bin/bash

# Production Deployment Preparation Script
# This script prepares only the safe files for production deployment

echo "========================================="
echo "Production Deployment Preparation"
echo "========================================="
echo ""

PROD_DIR="production-deploy"

# Create production deployment directory
rm -rf "$PROD_DIR"
mkdir -p "$PROD_DIR/app/Providers"

echo "1. Copying production-safe files..."
echo ""

# Copy ONLY the production-safe file
cp app/Providers/AppServiceProvider.php "$PROD_DIR/app/Providers/"

echo "✅ Copied: app/Providers/AppServiceProvider.php"
echo ""

echo "2. Verifying files..."
echo ""

# Verify the file
if [ -f "$PROD_DIR/app/Providers/AppServiceProvider.php" ]; then
    echo "✅ File exists"
    
    # Check for production-safe code
    if grep -q "Illuminate.*Support.*Facades.*URL::forceScheme" "$PROD_DIR/app/Providers/AppServiceProvider.php"; then
        echo "✅ Contains production-safe code"
    else
        echo "❌ Missing production fix!"
        exit 1
    fi
else
    echo "❌ File not found!"
    exit 1
fi

echo ""
echo "3. Creating deployment instructions..."
cat > "$PROD_DIR/DEPLOY_INSTRUCTIONS.txt" << 'EOF'
# Production Deployment Instructions

## Files to Deploy

ONLY deploy this file to production:
- app/Providers/AppServiceProvider.php

## Deployment Steps

1. Backup current file on production:
   cp app/Providers/AppServiceProvider.php app/Providers/AppServiceProvider.php.backup

2. Upload the new file:
   - Upload: app/Providers/AppServiceProvider.php
   - To: /path/to/psf_v4_report_v2/app/Providers/AppServiceProvider.php

3. Clear Laravel caches:
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear

4. Test:
   - Visit: http://psf.nchads.gov.kh
   - Should work without errors

## DO NOT Deploy

❌ vendor/ directory files (NEVER deploy vendor files)
❌ Local PHP 8.3 compatibility patches
❌ Any other modified files

## Verification

After deployment, verify:
- File syntax: php -l app/Providers/AppServiceProvider.php
- Application loads: http://psf.nchads.gov.kh
EOF

echo "✅ Created: DEPLOY_INSTRUCTIONS.txt"
echo ""

echo "========================================="
echo "✅ Production deployment package ready!"
echo "========================================="
echo ""
echo "Location: $PROD_DIR/"
echo ""
echo "Files included:"
ls -lh "$PROD_DIR/"
echo ""
echo "Next steps:"
echo "1. Review files in: $PROD_DIR/"
echo "2. Upload ONLY app/Providers/AppServiceProvider.php to production"
echo "3. Follow instructions in: $PROD_DIR/DEPLOY_INSTRUCTIONS.txt"
echo ""

