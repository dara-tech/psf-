# Deep Analysis: Why http://psf.nchads.gov.kh is Not Working

## Issues Found and Fixed

### 1. **CRITICAL: Syntax Error in AppServiceProvider.php** ✅ FIXED
   - **Problem**: Missing `use Illuminate\Support\Facades\URL;` import statement
   - **Error**: `Parse error: syntax error, unexpected 'public' (T_PUBLIC) at line 29`
   - **Impact**: Application could not boot, causing complete failure
   - **Fix Applied**: Added the missing import statement

### 2. **HTTPS Forcing Issue** ✅ FIXED
   - **Problem**: Code was forcing HTTPS scheme when environment was not 'local'
   - **Location**: `app/Providers/AppServiceProvider.php` line 19-21
   - **Impact**: When accessing via HTTP (`http://psf.nchads.gov.kh`), the application would try to force HTTPS, potentially causing:
     - Redirect loops
     - Connection refused errors
     - SSL/TLS handshake failures if HTTPS is not properly configured
   - **Fix Applied**: Modified to detect the actual request scheme and force the matching scheme (HTTP or HTTPS) based on the actual request, not just APP_URL

### 2a. **APP_URL Configuration Mismatch** ✅ HANDLED
   - **Problem**: `.env` file has `APP_URL=https://psf.nchads.gov.kh` but accessing via `http://psf.nchads.gov.kh`
   - **Impact**: Laravel might generate HTTPS URLs even when accessing via HTTP, causing:
     - Redirect loops
     - Mixed content warnings
     - Session cookie issues
   - **Fix Applied**: Updated AppServiceProvider to respect the actual request scheme (HTTP/HTTPS) rather than relying solely on APP_URL configuration
   - **Recommendation**: 
     - If using HTTP: Set `APP_URL=http://psf.nchads.gov.kh` in `.env`
     - If using HTTPS: Ensure SSL certificate is properly configured and access via `https://psf.nchads.gov.kh`
     - Current fix allows both to work, but matching APP_URL to actual access method is recommended

### 3. **Database Connection Issues** ⚠️ NEEDS ATTENTION
   - **Problem**: Database connection refused errors found in logs
   - **Error**: `SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it.`
   - **Impact**: Any page requiring database access will fail
   - **Possible Causes**:
     - Database server is not running
     - Incorrect database host/port in `.env` file
     - Firewall blocking database connections
     - Database credentials are incorrect
   - **Action Required**: 
     - Check `.env` file for correct database configuration:
       - `DB_HOST`
       - `DB_PORT`
       - `DB_DATABASE`
       - `DB_USERNAME`
       - `DB_PASSWORD`
     - Verify database server is running
     - Test database connection manually

## Configuration Recommendations

### Environment Variables (.env file)
Ensure these are properly configured on the production server:

```env
APP_ENV=local  # or 'production' for production
APP_DEBUG=true  # Set to false in production
APP_URL=http://psf.nchads.gov.kh  # Match this to your actual access method (http or https)

# Session Configuration (for HTTP access)
SESSION_DOMAIN=.nchads.gov.kh  # Keep the leading dot
SESSION_SECURE_COOKIE=false  # MUST be false on HTTP
SESSION_SAME_SITE=lax  # use 'lax' or 'strict'; DO NOT use 'none'
SESSION_DRIVER=file  # or redis if you use it

DB_CONNECTION=mysql
DB_HOST=127.0.0.1  # or your database server IP
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Server Configuration
1. **Web Server**: Ensure Apache/Nginx is running and configured correctly
2. **PHP**: Verify PHP version compatibility (Laravel 5.x requires PHP >= 7.1)
3. **Permissions**: Check file permissions on `storage/` and `bootstrap/cache/` directories
4. **SSL/HTTPS**: If you want to use HTTPS, ensure SSL certificate is properly configured

## Testing Steps

After applying fixes:
1. Clear application cache: `php artisan cache:clear`
2. Clear config cache: `php artisan config:clear`
3. Clear route cache: `php artisan route:clear`
4. Clear view cache: `php artisan view:clear`
5. Test database connection: `php artisan tinker` then try `DB::connection()->getPdo();`
6. Access the site: `http://psf.nchads.gov.kh`

## Additional Notes

- The application uses Laravel framework
- Session files show the application was previously accessed at `http://psf.nchads.gov.kh`
- TrustProxies middleware is configured to trust all proxies (`$proxies = '*'`)
- CSRF protection is enabled for web routes

## Next Steps

1. ✅ Fixed syntax error in AppServiceProvider
2. ✅ Fixed HTTPS forcing issue
3. ⚠️ **ACTION REQUIRED**: Verify and fix database connection settings
4. Test the application after database is configured
5. Consider setting up proper SSL/HTTPS if needed for production

