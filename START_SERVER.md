# How to Run the Application

## Quick Start

### Option 1: Laravel Development Server (Recommended for Testing)

```bash
cd /Users/cheolsovandara/Documents/D/Developments/2026/psf_v4_report_v2
php artisan serve
```

Then open in browser: **http://127.0.0.1:8000**

### Option 2: Laravel Development Server (Custom Port)

```bash
php artisan serve --port=8080
```

Then open in browser: **http://127.0.0.1:8080**

### Option 3: Laravel Development Server (All Interfaces)

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Then access from other devices on your network.

---

## For Production Server

### Using Apache/Nginx

The application should already be configured to run via your web server (Apache/Nginx).

1. **Ensure web server is running:**
   ```bash
   # For Apache
   sudo systemctl status apache2
   # or
   sudo service apache2 status
   
   # For Nginx
   sudo systemctl status nginx
   # or
   sudo service nginx status
   ```

2. **Access the site:**
   - Open browser: `http://psf.nchads.gov.kh`
   - Or: `https://psf.nchads.gov.kh` (if HTTPS is configured)

---

## Before Running - Important Steps

### 1. Clear Caches (Required)

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 2. Verify .env Configuration

Make sure your `.env` file has correct settings:
- `APP_URL=http://psf.nchads.gov.kh` (or https://)
- Database credentials are correct
- `APP_ENV=production` (or `local` for development)

### 3. Check Database Connection

```bash
php artisan tinker
# Then in tinker:
DB::connection()->getPdo();
# Should return PDO object without errors
```

---

## Troubleshooting

### Issue: "Class not found" errors
**Solution:** Run `composer install` to install dependencies

### Issue: "Permission denied" errors
**Solution:** 
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Issue: Database connection errors
**Solution:** Check database credentials in `.env` file

### Issue: Server won't start
**Solution:** 
- Check if port is already in use
- Try a different port: `php artisan serve --port=8080`
- Check PHP version: `php -v` (should be >= 7.1)

---

## Testing the Application

After starting the server:

1. **Test Home Page:**
   - Visit: `http://127.0.0.1:8000/`
   - Should redirect to `/home` or `/login`

2. **Test Login Page:**
   - Visit: `http://127.0.0.1:8000/login`
   - Should show login form

3. **Test API (if needed):**
   - Visit: `http://127.0.0.1:8000/api/v1/getTableList`
   - Should return JSON response

---

## Stop the Server

Press `Ctrl + C` in the terminal where the server is running.

---

## Production Deployment

For production, use a proper web server (Apache/Nginx) with PHP-FPM, not the development server.

The development server (`php artisan serve`) is only for local testing and development.

