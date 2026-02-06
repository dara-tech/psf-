# Production CSS Not Loading - Fix Applied

## Problem
CSS and JavaScript assets are not loading on production server when accessing via HTTP or HTTPS, even though the application runs on both.

## Root Cause
The views were using `url()` helper which generates absolute URLs based on `APP_URL` configuration. When `APP_URL` doesn't match the actual request scheme (HTTP vs HTTPS), assets are generated with the wrong scheme, causing:
- Mixed content errors (HTTPS page trying to load HTTP assets)
- Broken asset links (HTTP page trying to load HTTPS assets)
- CSS/JS not loading

## Solution Applied

### 1. Changed Asset Helpers in Views
**Files Updated:**
- `resources/views/partials/head.blade.php`
- `resources/views/partials/javascripts.blade.php`

**Changed from:**
```blade
{{ url('adminlte/css/style.css') }}
```

**Changed to:**
```blade
{{ asset('adminlte/css/style.css') }}
```

**Why:** The `asset()` helper automatically uses the current request scheme, while `url()` uses `APP_URL` which might not match the actual request.

### 2. Enhanced AppServiceProvider
**File Updated:** `app/Providers/AppServiceProvider.php`

**Added:** Automatic asset URL scheme detection based on actual request:
```php
// Also set the asset URL scheme to match the request scheme
if (request()->secure()) {
    \Illuminate\Support\Facades\URL::forceRootUrl('https://' . request()->getHttpHost());
} else {
    \Illuminate\Support\Facades\URL::forceRootUrl('http://' . request()->getHttpHost());
}
```

This ensures assets are always generated with the correct scheme matching the current request.

## Deployment Instructions

### 1. Upload Updated Files
Upload these files to production:
- `app/Providers/AppServiceProvider.php`
- `resources/views/partials/head.blade.php`
- `resources/views/partials/javascripts.blade.php`

### 2. Clear ALL Caches (CRITICAL)
```bash
cd /path/to/psf_v4_report_v2

# Clear all Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Clear optimized files
php artisan optimize:clear

# If using opcache, restart PHP/Apache
sudo service apache2 restart
# OR
sudo service php-fpm restart
```

### 3. Verify .env Configuration
Ensure `APP_URL` in `.env` matches your primary access method:
```env
# If primarily using HTTP:
APP_URL=http://psf.nchads.gov.kh

# If primarily using HTTPS:
APP_URL=https://psf.nchads.gov.kh
```

**Note:** With the fix applied, both HTTP and HTTPS will work, but setting `APP_URL` to match your primary access method is recommended.

### 4. Test
1. Clear browser cache (Ctrl+Shift+Delete or Cmd+Shift+Delete)
2. Access via HTTP: `http://psf.nchads.gov.kh`
3. Access via HTTPS: `https://psf.nchads.gov.kh`
4. Verify CSS/JS loads correctly in both cases
5. Check browser console (F12) for any asset loading errors

## What This Fix Does

1. **Automatic Scheme Detection:** Assets now use the same scheme (HTTP/HTTPS) as the current request
2. **No Mixed Content:** Prevents HTTPS pages from trying to load HTTP assets
3. **Works Both Ways:** Application works correctly whether accessed via HTTP or HTTPS
4. **No Configuration Changes Needed:** Works automatically based on request

## Additional Notes

- The fix is **production-safe** and **backward compatible**
- No database changes required
- No additional server configuration needed
- Works with Apache, Nginx, and other web servers
- Compatible with proxy/load balancer setups

## Troubleshooting

If CSS still doesn't load after deployment:

1. **Check file permissions:**
   ```bash
   chmod -R 755 public/adminlte
   chown -R www-data:www-data public/adminlte
   ```

2. **Verify assets exist:**
   ```bash
   ls -la public/adminlte/css/
   ls -la public/adminlte/js/
   ```

3. **Check Apache/Nginx configuration:**
   - Ensure `public` directory is the document root
   - Verify `.htaccess` is working (for Apache)
   - Check for any URL rewriting issues

4. **Browser Developer Tools:**
   - Open browser console (F12)
   - Check Network tab for failed asset requests
   - Verify the asset URLs are correct

5. **Test asset URL directly:**
   - Try accessing: `http://psf.nchads.gov.kh/adminlte/css/AdminLTE.min.css`
   - Should return the CSS file, not a 404

