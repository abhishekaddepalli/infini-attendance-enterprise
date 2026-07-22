# cPanel Deployment Guide for Infini Attendance Enterprise

Follow this complete step-by-step guide to deploy Infini Attendance to your cPanel hosting environment.

---

## Architecture Overview

On cPanel hosting:
1. **Laravel Backend**: Placed outside `public_html` (e.g. `/home/username/backend`) for security.
2. **Backend Public Folder**: The contents of `backend/public/` are placed inside `public_html/api` (or symlinked).
3. **React Frontend**: The compiled static files inside `frontend/dist/` are placed directly in `public_html/`.

---

## Step 1: MySQL Database Setup in cPanel

1. Log into your **cPanel**.
2. Go to **MySQL® Databases**.
3. Create a new database: e.g. `youruser_infini`.
4. Create a database user: e.g. `youruser_dbuser` with a strong password.
5. Add the user to the database and select **ALL PRIVILEGES**.

---

## Step 2: Upload & Configure Backend

1. Compress the `backend` directory into a `.zip` file on your local machine.
2. In cPanel **File Manager**, navigate to the root directory `/home/username/` (one level above `public_html`).
3. Upload and extract `backend.zip` so the path is `/home/username/backend`.
4. Edit `/home/username/backend/.env` using cPanel Code Editor and update your database credentials:
   ```env
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=youruser_infini
   DB_USERNAME=youruser_dbuser
   DB_PASSWORD=your_secure_password
   ```

---

## Step 3: Configure Public Access

1. Copy all contents from `/home/username/backend/public/` into `/home/username/public_html/api/`.
2. Edit `/home/username/public_html/api/index.php`:
   Update lines 11 and 18 to point to the backend folder path:
   ```php
   require __DIR__.'/../../backend/vendor/autoload.php';
   (require_once __DIR__.'/../../backend/bootstrap/app.php')
   ```

---

## Step 4: Build & Upload Frontend

1. On your computer in terminal, navigate to `frontend`:
   ```bash
   cd frontend
   npm install
   npm run build
   ```
2. Compress the generated `frontend/dist` folder into a zip.
3. Upload and extract the contents directly into `/home/username/public_html/`.
4. Verify `.htaccess` is present in `public_html/`:
   ```apache
   <IfModule mod_rewrite.c>
     RewriteEngine On
     RewriteBase /
     RewriteRule ^index\.html$ - [L]
     RewriteCond %{REQUEST_FILENAME} !-f
     RewriteCond %{REQUEST_FILENAME} !-d
     RewriteCond %{REQUEST_FILENAME} !-l
     RewriteRule . /index.html [L]
   </IfModule>
   ```

---

## Step 5: Run Installer Wizard

Navigate to `https://yourdomain.com/installer/index.php` in your web browser to verify PHP extensions and complete database setup.

---

## Step 6: Set Up Cron Jobs in cPanel

1. In cPanel, open **Cron Jobs**.
2. Add a new cron job running every minute (`* * * * *`):
   ```bash
   /usr/local/bin/php /home/username/backend/artisan schedule:run >> /dev/null 2>&1
   ```

---

🎉 **Congratulations! Infini Attendance Enterprise is now live on your cPanel server.**
