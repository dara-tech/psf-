# HTTPS Mixed Content Fix - Assets Loading as HTTP

## Problem
When accessing the site via HTTPS (`https://psf.nchads.gov.kh`), CSS and JavaScript assets are being generated as HTTP URLs, causing browser mixed content blocking:
```
[blocked] The page at https://psf.nchads.gov.kh/login requested insecure content from http://psf.nchads.gov.kh/adminlte/bootstrap/css/bootstrap.min.css
```

## Root Cause
The `request()->secure()` method wasn't correctly detecting HTTPS when behind a proxy/load balancer, even though TrustProxies middleware is configured. This caused assets to be generated with HTTP scheme even when accessing via HTTPS.

## Solution Applied

### Enhanced HTTPS Detection
**File Updated:** `app/Providers/AppServiceProvider.php`

**Improved detection to handle multiple scenarios:**
1. Direct HTTPS connections (`request()->secure()`)
2. Behind proxy with `X-Forwarded-Proto` header
3. Server `HTTPS` environment variable
4. `REQUEST_SCHEME` server variable

**New Code:**
```php
// Detect if request is secure (handles both direct HTTPS and proxy scenarios)
$isSecure = request()->secure() || 
           request()->server('HTTP_X_FORWARDED_PROTO') === 'https' ||
           request()->server('HTTPS') === 'on' ||
           (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https');

// Set the root URL first with correct scheme
$host = request()->getHttpHost();
if ($isSecure) {
    \Illuminate\Support\Facades\URL::forceRootUrl('https://' . $host);
    \Illuminate\Support\Facades\URL::forceScheme('https');
} else {
    \Illuminate\Support\Facades\URL::forceRootUrl('http://' . $host);
    \Illuminate\Support\Facades\URL::forceScheme('http');
}
```

## Deployment Instructions

### 1. Upload Updated File
Upload `app/Providers/AppServiceProvider.php` to production server.

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

### 3. Verify TrustProxies Middleware
Ensure `app/Http/Middleware/TrustProxies.php` has:
```php
protected $proxies = '*';
protected $headers = \Illuminate\Http\Request::HEADER_X_FORWARDED_ALL;
```

### 4. Test
1. Clear browser cache completely (Ctrl+Shift+Delete)
2. Access via HTTPS: `https://psf.nchads.gov.kh/login`
3. Open browser Developer Tools (F12)
4. Check Network tab - all asset requests should be HTTPS
5. Check Console - no mixed content warnings
6. Verify CSS/JS loads correctly

## What This Fix Does

1. **Multi-Method HTTPS Detection:** Checks multiple ways to detect HTTPS, ensuring it works behind proxies, load balancers, and direct connections
2. **Correct Asset URLs:** All assets (CSS/JS) are generated with the same scheme as the page (HTTPS)
3. **No Mixed Content:** Prevents browser blocking of HTTP resources on HTTPS pages
4. **Works Both Ways:** Still supports HTTP access when needed

## Troubleshooting

If assets still load as HTTP after deployment:

1. **Check if code is deployed:**
   ```bash
   grep -A 10 "isSecure" app/Providers/AppServiceProvider.php
   ```
   Should show the new detection code.

2. **Verify caches are cleared:**
   ```bash
   php artisan config:clear
   php artisan view:clear
   ```

3. **Check Apache/Nginx proxy headers:**
   - Ensure `X-Forwarded-Proto` header is being set by your proxy/load balancer
   - For Apache behind proxy, you may need:
     ```apache
     RequestHeader set X-Forwarded-Proto "https"
     ```

4. **Test HTTPS detection:**
   Create a test route to debug:
   ```php
   Route::get('/test-https', function() {
       return [
           'secure()' => request()->secure(),
           'X-Forwarded-Proto' => request()->server('HTTP_X_FORWARDED_PROTO'),
           'HTTPS' => request()->server('HTTPS'),
           'REQUEST_SCHEME' => $_SERVER['REQUEST_SCHEME'] ?? 'not set',
           'asset_url' => asset('adminlte/css/AdminLTE.min.css'),
       ];
   });
   ```
   Access `https://psf.nchads.gov.kh/test-https` and verify:
   - At least one detection method returns true
   - `asset_url` starts with `https://`

5. **Check browser console:**
   - Open Developer Tools (F12)
   - Check Network tab for asset requests
   - Verify all requests use HTTPS scheme

## Additional Notes

- This fix is **production-safe** and **backward compatible**
- Works with Apache, Nginx, and other web servers
- Compatible with proxy/load balancer setups (AWS ELB, CloudFlare, etc.)
- No database changes required
- No additional server configuration needed (if TrustProxies is already configured)

