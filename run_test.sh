#!/bin/bash

echo "========================================="
echo "Testing Laravel Application"
echo "========================================="
echo ""

# Change to project directory
cd "$(dirname "$0")"

echo "1. Checking PHP version..."
php -v | head -1
echo ""

echo "2. Checking Laravel installation..."
php artisan --version 2>&1 | grep -E "(Laravel|version)" | head -1
echo ""

echo "3. Verifying AppServiceProvider syntax..."
if php -l app/Providers/AppServiceProvider.php > /dev/null 2>&1; then
    echo "   ✓ Syntax is valid"
else
    echo "   ✗ Syntax errors found"
    php -l app/Providers/AppServiceProvider.php
fi
echo ""

echo "4. Checking if application can boot..."
php -r "
require 'vendor/autoload.php';
try {
    \$app = require_once 'bootstrap/app.php';
    echo '   ✓ Application boots successfully';
} catch (Exception \$e) {
    echo '   ✗ Application failed to boot: ' . \$e->getMessage();
}
" 2>&1 | grep -E "(✓|✗|Application)"
echo ""

echo "5. Testing URL facade availability..."
php -r "
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
if (class_exists('Illuminate\Support\Facades\URL')) {
    echo '   ✓ URL facade is available';
} else {
    echo '   ✗ URL facade not found';
}
" 2>&1 | grep -E "(✓|✗|URL)"
echo ""

echo "========================================="
echo "To start the development server, run:"
echo "  php artisan serve"
echo ""
echo "Then access: http://127.0.0.1:8000"
echo "========================================="

