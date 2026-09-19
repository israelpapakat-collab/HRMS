#!/bin/bash
# Deployment Script for Ubuntu + Nginx + MySQL + Laravel

set -e

# --- Server Setup ---
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mysql-server php8.3-fpm php8.3-cli php8.3-mysql \
    php8.3-xml php8.3-mbstring php8.3-curl php8.3-zip php8.3-bcmath \
    php8.3-gd composer certbot python3-certbot-nginx

# --- MySQL Setup ---
sudo mysql -e "CREATE DATABASE IF NOT EXISTS dblsite CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER IF NOT EXISTS 'dblsite'@'localhost' IDENTIFIED BY 'your-strong-password';"
sudo mysql -e "GRANT ALL PRIVILEGES ON dblsite.* TO 'dblsite'@'localhost'; FLUSH PRIVILEGES;"

# --- Application Setup ---
cd /var/www/html
sudo git clone <your-repo-url> dblsite
cd dblsite
sudo chown -R www-data:www-data storage bootstrap/cache

cp .env.example .env
# Edit .env with database credentials and app settings

# --- Install Dependencies ---
composer install --no-dev --optimize-autoloader
npm install && npm run build

# --- Laravel Setup ---
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

# --- Nginx Setup ---
sudo cp deploy/nginx.conf /etc/nginx/sites-available/dblsite
sudo ln -sf /etc/nginx/sites-available/dblsite /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx

# --- SSL (Let's Encrypt) ---
# sudo certbot --nginx -d your-domain.com

# --- Schedule Backups ---
# sudo crontab -e
# 0 3 * * * /usr/bin/mysqldump -u dblsite -p'password' dblsite > /var/backups/dblsite_$(date +\%Y\%m\%d).sql

# --- Schedule Laravel Tasks ---
# sudo crontab -e -u www-data
# * * * * * cd /var/www/html/dblsite && php artisan schedule:run >> /dev/null 2>&1

echo "Deployment complete!"
