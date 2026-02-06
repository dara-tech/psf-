# Changes Needed for http://psf.nchads.gov.kh

## ✅ Already Fixed (Code Changes)

### 1. **AppServiceProvider.php** - ALREADY FIXED
   - ✅ Added missing `use Illuminate\Support\Facades\URL;` import
   - ✅ Fixed HTTPS forcing logic to handle both HTTP and HTTPS
   - ✅ No action needed - file is already updated

---

## ⚠️ Changes You Need to Make

### 1. **Update .env File** (REQUIRED)

Based on your screenshot, you need to update your `.env` file on the production server:

**Current (from your screenshot):**
```env
APP_URL=https://psf.nchads.gov.kh
```

**Recommended Change (if using HTTP):**
```env
APP_URL=http://psf.nchads.gov.kh
```

**Why?** While the code fix allows both to work, matching `APP_URL` to your actual access method prevents confusion and ensures proper URL generation.

**Complete .env Configuration Checklist:**
```env
# Application
APP_NAME="PSF Report"
APP_ENV=production          # or 'local' for development
APP_KEY=base64:4DFpu4zCemHwAaP752TIhBXJL4PlZcREHlaCxZZA/aI=
APP_DEBUG=false             # Set to true only for debugging
APP_URL=http://psf.nchads.gov.kh  # ⚠️ CHANGE THIS if using HTTP

# Session (for HTTP access)
SESSION_DOMAIN=.nchads.gov.kh     # Keep the leading dot
SESSION_SECURE_COOKIE=false       # MUST be false on HTTP
SESSION_SAME_SITE=lax             # use 'lax' or 'strict'
SESSION_DRIVER=file               # or redis if you use it

# Database - ⚠️ VERIFY THESE ARE CORRECT
DB_CONNECTION=mysql
DB_HOST=127.0.0.1                 # or your database server IP
DB_PORT=3306
DB_DATABASE=your_database_name   # ⚠️ UPDATE THIS
DB_USERNAME=your_username         # ⚠️ UPDATE THIS
DB_PASSWORD=your_password        # ⚠️ UPDATE THIS

# Logging
LOG_CHANNEL=stack
```

---

### 2. **Clear Laravel Caches** (REQUIRED)

After deploying the fixed code and updating `.env`, run these commands on your production server:

```bash
cd /path/to/psf_v4_report_v2

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# If you have optimized files, clear them too
php artisan optimize:clear
```

**Why?** Laravel caches configuration. Clearing ensures your changes take effect.

---

### 3. **Verify Database Connection** (REQUIRED)

The logs show database connection errors. You need to:

**a) Check Database Server:**
```bash
# Test if database server is running
mysql -h 127.0.0.1 -u your_username -p
```

**b) Verify .env Database Settings:**
- `DB_HOST` - Should be `127.0.0.1` or your database server IP
- `DB_PORT` - Usually `3306` for MySQL
- `DB_DATABASE` - Your actual database name
- `DB_USERNAME` - Your database username
- `DB_PASSWORD` - Your database password

**c) Test Connection via Laravel:**
```bash
php artisan tinker
# Then in tinker:
DB::connection()->getPdo();
# Should return PDO object, not an error
```

---

### 4. **Check File Permissions** (RECOMMENDED)

Ensure these directories are writable:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

Or set ownership:
```bash
chown -R www-data:www-data storage bootstrap/cache
```

---

### 5. **Verify Web Server Configuration** (RECOMMENDED)

**For Apache (.htaccess should be working):**
- Ensure `mod_rewrite` is enabled
- Document root should point to `public/` directory

**For Nginx:**
- Ensure proper rewrite rules are configured
- Document root should point to `public/` directory

---

## 📋 Deployment Checklist

Before testing `http://psf.nchads.gov.kh`:

- [ ] Deploy updated `app/Providers/AppServiceProvider.php` to production
- [ ] Update `.env` file with correct `APP_URL` (http://psf.nchads.gov.kh)
- [ ] Verify database credentials in `.env` are correct
- [ ] Clear all Laravel caches (`php artisan config:clear`, etc.)
- [ ] Verify database server is running and accessible
- [ ] Check file permissions on `storage/` and `bootstrap/cache/`
- [ ] Test database connection
- [ ] Access `http://psf.nchads.gov.kh` in browser

---

## 🔍 What to Test

1. **Basic Access:**
   - Visit `http://psf.nchads.gov.kh` - should load without errors
   - Should redirect to `/home` or `/login`

2. **Login Page:**
   - Visit `http://psf.nchads.gov.kh/login` - should display login form
   - No redirect loops or SSL errors

3. **Database-Dependent Pages:**
   - After login, pages requiring database should work
   - No "connection refused" errors

4. **Session:**
   - Login should create session
   - Session should persist across page loads

---

## 🚨 Common Issues After Deployment

### Issue: Still getting redirect loops
**Solution:** 
- Clear config cache: `php artisan config:clear`
- Verify `APP_URL` in `.env` matches your access method

### Issue: Database connection errors
**Solution:**
- Verify database server is running
- Check database credentials in `.env`
- Test connection manually with `mysql` command

### Issue: 500 Internal Server Error
**Solution:**
- Check Laravel logs: `storage/logs/laravel.log`
- Verify file permissions
- Check PHP error logs

---

## Summary

**Code Changes:** ✅ Already done (AppServiceProvider.php)

**Configuration Changes Needed:**
1. ⚠️ Update `APP_URL` in `.env` to match your access method
2. ⚠️ Verify database credentials in `.env`
3. ⚠️ Clear Laravel caches after deployment
4. ⚠️ Test database connection

**Server Checks:**
1. ⚠️ Verify database server is running
2. ⚠️ Check file permissions
3. ⚠️ Verify web server configuration

