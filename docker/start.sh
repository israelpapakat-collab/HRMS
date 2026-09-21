#!/bin/sh
set -e

# Railway injects a dynamic PORT variable (default to 80 if not set)
PORT="${PORT:-80}"
echo "Configuring Nginx to listen on port ${PORT}..."
sed -i "s/listen [0-9]\+;/listen ${PORT};/g" /etc/nginx/http.d/default.conf

# Ensure storage and cache directory permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

php artisan package:discover --ansi 2>/dev/null || true
php artisan storage:link 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running database migrations..."
php artisan migrate --force || echo "WARNING: Database migration encountered an issue. Please verify database credentials."

if [ "$RUN_SEEDER" = "true" ]; then
  echo "Seeding initial data..."
  php artisan db:seed --force || echo "WARNING: Database seeding encountered an issue."
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
