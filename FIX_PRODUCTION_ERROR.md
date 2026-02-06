# Fix for Production Server Error

## Problem Found

The production server is showing this error:
```
Class 'App\Providers\URL' not found at app\Providers\AppServiceProvider.php:20
```

## Root Cause

The production server is not properly resolving the `URL` facade import. This can happen when:
1. Config cache is stale
2. Autoload cache is outdated
3. Namespace resolution issues

## Solution Applied

I've updated the code to use **fully qualified class names** instead of relying on the import. This ensures it works even if the import isn't properly resolved.

**Changed from:**
```php
URL::forceScheme('https');
```

**Changed to:**
```php
\Illuminate\Support\Facades\URL::forceScheme('https');
```

## What You Need to Do on Production Server

### 1. Deploy the Updated File
Upload the new `app/Providers/AppServiceProvider.php` to your production server.

### 2. Clear ALL Caches (CRITICAL)
```bash
cd /path/to/psf_v4_report_v2

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Clear optimized files
php artisan optimize:clear

# Regenerate autoload (if needed)
composer dump-autoload
```

### 3. Verify the Fix
After clearing caches, test the site:
- Visit: `http://psf.nchads.gov.kh`
- Should load without the "Class 'App\Providers\URL' not found" error

## Why This Happened

The production server was trying to find `URL` in the `App\Providers` namespace instead of using the imported facade from `Illuminate\Support\Facades\URL`. Using the fully qualified name (`\Illuminate\Support\Facades\URL`) ensures Laravel always finds the correct class, regardless of import resolution issues.

## Additional Notes

- The fix is backward compatible
- No other code changes needed
- Safe to deploy immediately
- Works with both HTTP and HTTPS

