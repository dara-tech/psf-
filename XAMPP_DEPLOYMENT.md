# XAMPP Deployment Guide

## For XAMPP Server (Windows)

Since your production server uses XAMPP, here are the specific steps for deployment:

## 1. File Deployment

### Upload Files to XAMPP
1. **Locate your XAMPP installation:**
   - Usually: `C:\xampp\htdocs\psf_v4_report_v2\`
   - Or wherever your project is located

2. **Upload/Replace these files:**
   - `app\Providers\AppServiceProvider.php` (Windows uses backslashes)
   - `resources\views\partials\head.blade.php`
   - `resources\views\partials\javascripts.blade.php`

## 2. Clear Caches (XAMPP)

Open **Command Prompt** or **XAMPP Shell** and run:

```cmd
cd C:\xampp\htdocs\psf_v4_report_v2

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

**Note:** Make sure PHP is in your PATH, or use full path:
```cmd
C:\xampp\php\php.exe artisan config:clear
```

## 3. XAMPP-Specific Configuration

### Check .env File Location
Your `.env` file should be in:
```
C:\xampp\htdocs\psf_v4_report_v2\.env
```

### Verify .env Settings for XAMPP
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://psf.nchads.gov.kh

# Database (XAMPP default MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=psf_db
DB_USERNAME=root
DB_PASSWORD=          # XAMPP default is empty, or your password

# Session (for HTTP)
SESSION_DOMAIN=.nchads.gov.kh
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax
SESSION_DRIVER=file
```

## 4. XAMPP Apache Configuration

### Ensure mod_rewrite is Enabled
1. Open `C:\xampp\apache\conf\httpd.conf`
2. Find and uncomment (remove `#`):
   ```apache
   LoadModule rewrite_module modules/mod_rewrite.so
   ```
3. Restart Apache from XAMPP Control Panel

### Virtual Host Setup (Recommended)
If using a domain name, add to `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    ServerName psf.nchads.gov.kh
    DocumentRoot "C:/xampp/htdocs/psf_v4_report_v2/public"
    
    <Directory "C:/xampp/htdocs/psf_v4_report_v2/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Important:** Use forward slashes `/` in paths, not backslashes `\`

### Enable .htaccess
In `httpd.conf`, find:
```apache
<Directory "C:/xampp/htdocs">
    AllowOverride None
```
Change to:
```apache
<Directory "C:/xampp/htdocs">
    AllowOverride All
```

## 5. HTTPS on XAMPP (If Needed)

### If You Need HTTPS:
1. **Enable SSL in Apache:**
   - Open `C:\xampp\apache\conf\httpd.conf`
   - Uncomment:
     ```apache
     LoadModule ssl_module modules/mod_ssl.so
     Include conf/extra/httpd-ssl.conf
     ```

2. **Configure SSL:**
   - Edit `C:\xampp\apache\conf\extra\httpd-ssl.conf`
   - Update paths and certificate locations

3. **For Production:** Use a real SSL certificate, not the XAMPP self-signed one

## 6. File Permissions (Windows)

On Windows, file permissions work differently:

1. **Right-click on these folders:**
   - `storage`
   - `bootstrap\cache`

2. **Properties → Security tab:**
   - Ensure `IIS_IUSRS` or `Everyone` has **Write** permissions
   - Or add your web server user with write permissions

## 7. Test Deployment

### 1. Start XAMPP Services
- Open XAMPP Control Panel
- Start **Apache**
- Start **MySQL**

### 2. Test Database Connection
```cmd
cd C:\xampp\htdocs\psf_v4_report_v2
php artisan tinker
```
Then in tinker:
```php
DB::connection()->getPdo();
```
Should return PDO object without errors.

### 3. Access the Site
- Open browser: `http://psf.nchads.gov.kh`
- Or: `http://localhost/psf_v4_report_v2/public`

### 4. Check for Errors
- Open browser Developer Tools (F12)
- Check Console for JavaScript errors
- Check Network tab for failed asset requests
- Verify CSS/JS loads correctly

## 8. Troubleshooting XAMPP Issues

### Issue: "500 Internal Server Error"
**Solution:**
1. Check Apache error log: `C:\xampp\apache\logs\error.log`
2. Check Laravel log: `storage\logs\laravel.log`
3. Verify `.htaccess` file exists in `public` folder
4. Ensure `mod_rewrite` is enabled

### Issue: "Class not found" errors
**Solution:**
```cmd
cd C:\xampp\htdocs\psf_v4_report_v2
composer install
composer dump-autoload
```

### Issue: CSS/JS not loading
**Solution:**
1. Clear all caches (see step 2)
2. Check browser console for mixed content warnings
3. Verify `APP_URL` in `.env` matches your access method
4. Check if assets exist in `public\adminlte\` folder

### Issue: Database connection failed
**Solution:**
1. Verify MySQL is running in XAMPP Control Panel
2. Check database credentials in `.env`
3. Test connection:
   ```cmd
   mysql -u root -p
   ```
4. Verify database exists:
   ```sql
   SHOW DATABASES;
   USE psf_db;
   SHOW TABLES;
   ```

### Issue: "Permission denied" errors
**Solution:**
1. Right-click `storage` and `bootstrap\cache` folders
2. Properties → Security → Edit
3. Add your user or `IIS_IUSRS` with Full Control

## 9. Production Checklist for XAMPP

- [ ] Files uploaded to correct XAMPP directory
- [ ] All caches cleared (`php artisan config:clear`, etc.)
- [ ] `.env` file configured correctly
- [ ] Apache `mod_rewrite` enabled
- [ ] Virtual host configured (if using domain)
- [ ] `.htaccess` file exists in `public` folder
- [ ] Apache and MySQL services running
- [ ] Database connection tested and working
- [ ] File permissions set correctly
- [ ] Site accessible via browser
- [ ] CSS/JS assets loading correctly
- [ ] No console errors in browser

## 10. Important Notes for XAMPP

1. **PHP Version:** XAMPP comes with a specific PHP version. Verify it's compatible:
   ```cmd
   php -v
   ```
   Laravel 5.x requires PHP >= 7.1

2. **Document Root:** Always point to `public` folder:
   ```
   C:\xampp\htdocs\psf_v4_report_v2\public
   ```

3. **Path Separators:** Use forward slashes `/` in Apache config, even on Windows

4. **HTTPS Detection:** The code will automatically detect HTTPS if:
   - SSL is properly configured
   - `X-Forwarded-Proto` header is set (if behind proxy)
   - Or accessing directly via HTTPS

5. **Windows Line Endings:** If you edit files on Windows, ensure line endings are correct (CRLF is fine for Windows)

## Quick Commands Reference

```cmd
# Navigate to project
cd C:\xampp\htdocs\psf_v4_report_v2

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Test database
php artisan tinker

# Check PHP version
php -v

# Check Apache status
# (Use XAMPP Control Panel)
```

## Support

If you encounter issues:
1. Check `storage\logs\laravel.log` for errors
2. Check `C:\xampp\apache\logs\error.log` for Apache errors
3. Enable `APP_DEBUG=true` temporarily to see detailed errors
4. Check browser console (F12) for JavaScript/CSS errors

