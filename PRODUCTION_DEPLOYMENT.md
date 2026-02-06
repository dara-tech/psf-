# Production Deployment Guide - Safe Changes Only

## ⚠️ IMPORTANT: What to Deploy vs What NOT to Deploy

### ✅ SAFE TO DEPLOY TO PRODUCTION

These files are **SAFE** and **REQUIRED** for production:

1. **`app/Providers/AppServiceProvider.php`** ✅ **DEPLOY THIS**
   - Fixes the critical syntax error
   - Fixes HTTP/HTTPS handling
   - **This is the main fix for production**

### ❌ DO NOT DEPLOY TO PRODUCTION

These files are **ONLY for local PHP 8.3 testing** and should **NOT** be deployed:

1. **`vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php`** ❌ **DO NOT DEPLOY**
   - This is a vendor file patch for PHP 8.3 compatibility
   - Production server (PHP 7.x) doesn't need this
   - **Never modify vendor files in production**

2. **`vendor/nesbot/carbon/src/Carbon/Carbon.php`** ❌ **DO NOT DEPLOY**
   - This is a vendor file patch for PHP 8.3 compatibility
   - Production server (PHP 7.x) doesn't need this
   - **Never modify vendor files in production**

3. **`bootstrap/autoload.php`** ⚠️ **OPTIONAL** (only if you want PHP 8.3 support on production)
   - Contains PHP 8.3 compatibility code
   - Production with PHP 7.x doesn't need this
   - Safe to deploy but not necessary

4. **`bootstrap/app.php`** ⚠️ **OPTIONAL** (only if you want PHP 8.3 support on production)
   - Contains PHP 8.3 compatibility code
   - Production with PHP 7.x doesn't need this
   - Safe to deploy but not necessary

5. **`public/index.php`** ⚠️ **OPTIONAL** (only if you want PHP 8.3 support on production)
   - Contains PHP 8.3 compatibility code
   - Production with PHP 7.x doesn't need this
   - Safe to deploy but not necessary

6. **`app/Exceptions/Handler.php`** ⚠️ **OPTIONAL** (only if you want PHP 8.3 support on production)
   - Contains PHP 8.3 compatibility code
   - Production with PHP 7.x doesn't need this
   - Safe to deploy but not necessary

## 📋 Production Deployment Checklist

### Step 1: Deploy Only the Critical Fix

**ONLY deploy this file:**
```
app/Providers/AppServiceProvider.php
```

This file contains:
- ✅ Fixed syntax error (missing URL import)
- ✅ Fixed HTTP/HTTPS URL scheme handling
- ✅ Uses fully qualified class names (works everywhere)

### Step 2: Verify What You're Deploying

Before deploying, check that you're **NOT** including:
- ❌ Any files in `vendor/` directory
- ❌ Modified vendor files
- ❌ Local-only PHP 8.3 compatibility patches

### Step 3: Production Server Steps

On your production server:

1. **Backup current file:**
   ```bash
   cp app/Providers/AppServiceProvider.php app/Providers/AppServiceProvider.php.backup
   ```

2. **Deploy the fixed file:**
   - Upload only: `app/Providers/AppServiceProvider.php`

3. **Clear caches:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

4. **Test:**
   - Visit: `http://psf.nchads.gov.kh`
   - Should work without errors

## 🔒 Why Vendor Files Should NOT Be Deployed

### Vendor Files Are Managed by Composer

- Vendor files are in `.gitignore` (not tracked in git)
- They get overwritten when running `composer install` or `composer update`
- Production server should use original vendor files
- PHP 7.x on production doesn't have the PHP 8.3 compatibility issues

### What Happens If You Deploy Vendor Files?

- ❌ They'll be overwritten on next `composer install`
- ❌ May cause issues if production PHP version is different
- ❌ Not a standard practice
- ❌ Hard to maintain

## ✅ Safe Deployment Summary

**Deploy to Production:**
- ✅ `app/Providers/AppServiceProvider.php` (REQUIRED)

**Do NOT Deploy:**
- ❌ `vendor/` directory files (NEVER)
- ❌ Local PHP 8.3 compatibility patches (NOT NEEDED)

**Optional (Safe but Not Required):**
- ⚠️ Bootstrap files with PHP 8.3 code (won't hurt, but not needed)

## 🎯 Quick Deployment Command

If using Git or FTP:

```bash
# Only deploy this file:
app/Providers/AppServiceProvider.php
```

That's it! One file is all you need for production.

## ✅ Verification

After deployment, verify:

1. **File is correct:**
   ```bash
   # On production server
   grep "Illuminate\\Support\\Facades\\URL::forceScheme" app/Providers/AppServiceProvider.php
   # Should show the fixed code
   ```

2. **No vendor files modified:**
   ```bash
   # Check if vendor files are original
   git status vendor/  # Should show nothing (if using git)
   ```

3. **Application works:**
   - Visit `http://psf.nchads.gov.kh`
   - Should load without errors

## 📝 Summary

- **Production needs:** Only `AppServiceProvider.php` fix
- **Local testing needs:** Vendor file patches (PHP 8.3 only)
- **Safe approach:** Deploy only the application code fix
- **Production PHP 7.x:** Doesn't need vendor patches

Your production server will work perfectly with just the `AppServiceProvider.php` fix!


