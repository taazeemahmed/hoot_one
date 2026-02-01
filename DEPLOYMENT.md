# Deployment Instructions for Hootone One

## 1. Server Prerequisites
Ensure your server (195.35.23.110) has the following installed:
- PHP >= 8.2
- Composer
- MySQL/MariaDB
- Nginx/Apache
- NodeJS & NPM

## 2. Setup (CyberPanel)
Since you are using CyberPanel:
1. Log in to CyberPanel.
2. Create a new Website: `one.hootone.org`.
   - Select PHP 8.2 or higher.
   - Check "SSL" if you want HTTPS immediately (recommended).
   - Check "Open_basedir Protection" (standard).

## 3. Deployment Steps (SSH)
Connect to your server via SSH. Navigate to the website's document root (usually `/home/one.hootone.org/public_html` or similar).

```bash
# SSH into your server
ssh root@195.35.23.110

# Delete default index file if exists
rm -rf /home/one.hootone.org/public_html/*

# Clone the repository
git clone https://github.com/taazeemahmed/hoot_one.git /home/one.hootone.org/public_html

# Navigate to directory
cd /home/one.hootone.org/public_html

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies and build assets
npm install
npm run build

# Setup Environment
cp .env.example .env
nano .env
# --> UPDATE DB_DATABASE, DB_USERNAME, DB_PASSWORD with your CyberPanel DB details
# --> SET APP_ENV=production
# --> SET APP_DEBUG=false
# --> SET APP_URL=https://one.hootone.org

# Generate Key
php artisan key:generate

# Storage Linking
php artisan storage:link

# Fix Permissions (CyberPanel user is usually 'nobody' or specific user, check owner)
# If created via CyberPanel, the owner might be unique. Use 'ls -la' to check.
# Example: chown -R 1001:1001 . (Replacing 1001 with actual user ID)
# OR generic:
chmod -R 775 storage bootstrap/cache
```

## 4. Database Import
1. Create a database in CyberPanel -> Databases -> Create Database.
   - Database Name: `onehoot_db` (example)
   - User: `onehoot_user`
2. Upload the `hootone_production.sql` file from your local machine to the server.
   
   **From Local Terminal:**
   ```bash
   scp hootone_production.sql root@195.35.23.110:/home/one.hootone.org/public_html/
   ```

3. Import on Server:
   ```bash
   mysql -u onehoot_user -p onehoot_db < hootone_production.sql
   ```

## 5. Finalize
- Run Migrations (if any drift): `php artisan migrate --force`
- Restart PHP (CyberPanel -> PHP Status -> Restart PHP)

Your site should now be live at https://one.hootone.org
