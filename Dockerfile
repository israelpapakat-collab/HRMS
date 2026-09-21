FROM composer:2 AS composer-deps
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --ignore-platform-reqs --no-scripts
COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts

FROM node:20-alpine AS node-build
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM php:8.2-fpm-alpine

# Install web server, process manager, and system tools
RUN apk add --no-cache nginx supervisor bash curl

# Install official PHP extension helper
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

# Install PHP extensions required by Laravel & HRMS
RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    pdo_mysql \
    mbstring \
    xml \
    zip \
    gd \
    bcmath \
    intl \
    opcache

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
