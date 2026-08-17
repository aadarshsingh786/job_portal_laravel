# ============================================================
# Stage 1: Build frontend assets (Vite / Tailwind / Alpine)
# ============================================================
FROM node:22-alpine AS assets

WORKDIR /app

COPY package.json package-lock.json ./
COPY vite.config.js postcss.config.js tailwind.config.js ./

RUN npm ci --no-audit --no-fund

COPY resources/ ./resources/
COPY public/ ./public/

RUN npm run build

# ============================================================
# Stage 2: Install PHP dependencies (composer)
# ============================================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-autoloader \
    --prefer-dist

# ============================================================
# Stage 3: Runtime (PHP-FPM)
# ============================================================
FROM php:8.3-fpm-alpine

# System dependencies + PHP extensions
RUN apk add --no-cache \
        nginx \
        supervisor \
        libpng \
        libjpeg-turbo \
        freetype \
        libzip \
        icu \
        oniguruma \
    && apk add --no-cache --virtual .build-deps \
        $PHPIZE_DEPS \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        libzip-dev \
        icu-dev \
        oniguruma-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
    && apk del .build-deps \
    && rm -rf /var/cache/apk/*

# PHP settings
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Nginx + Supervisor config
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

WORKDIR /var/www/html

# Copy app source
COPY --chown=www-data:www-data . .
# Copy built vendor + assets from earlier stages
COPY --chown=www-data:www-data --from=vendor /app/vendor ./vendor
COPY --chown=www-data:www-data --from=assets /app/public/build ./public/build

# Storage directories + permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Container startup
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
