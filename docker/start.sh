#!/bin/sh
set -e

# Railway injects a dynamic PORT variable (default to 80 if not set)
PORT="${PORT:-80}"
echo "Configuring Nginx to listen on port ${PORT}..."
sed -i "s/listen [0-9]\+;/listen ${PORT};/g" /etc/nginx/http.d/default.conf

# Ensure storage and cache directory structures and permissions
mkdir -p /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/storage/app/public \
         /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Failsafe: Ensure an APP_KEY is present
if [ -z "$APP_KEY" ]; then
  echo "WARNING: APP_KEY not provided. Generating a runtime application key..."
  php artisan key:generate --force
fi

php artisan package:discover --ansi 2>/dev/null || true
php artisan storage:link 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Wait for database availability before migrating
echo "Checking database connection..."
for i in $(seq 1 15); do
  if php artisan migrate:status >/dev/null 2>&1; then
    echo "Database is connected and ready."
    break
  fi
  echo "Waiting for database to accept connections ($i/15)..."
  sleep 2
done

echo "Running database migrations..."
php artisan migrate --force || echo "WARNING: Database migration encountered an issue. Please verify database credentials."

if [ "$RUN_SEEDER" = "true" ]; then
  echo "Seeding initial data..."
  php artisan db:seed --force || echo "WARNING: Database seeding encountered an issue."
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
