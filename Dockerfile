FROM composer:2 AS composer-deps
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --no-dev
FROM node:20-alpine AS node-build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --prefer-offline
COPY . .
RUN npm run build
FROM php:8.2-fpm-alpine
RUN apk add --no-cache nginx supervisor bash curl libpng-dev libzip-dev libxml2-dev postgresql-dev oniguruma-dev icu-dev
RUN docker-php-ext-configure intl && docker-php-ext-install pdo pdo_pgsql pgsql mbstring xml zip gd bcmath intl opcache
WORKDIR /var/www/html
COPY --from=composer-deps /app /var/www/html
COPY --from=node-build /app/public/build /var/www/html/public/build
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
EXPOSE 80
CMD ["/start.sh"]
