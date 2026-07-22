FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev \
    libxml2-dev \
    libzip-dev \
    icu-dev \
    libsodium-dev \
    imagemagick \
    imagemagick-dev \
    mysql-client \
    redis \
    bash

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
    pdo pdo_mysql mysqli gd zip intl xml dom \
    mbstring exif pcntl bcmath opcache sodium sockets calendar \
    && pecl install redis imagick \
    && docker-php-ext-enable redis imagick

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY backend/composer.json backend/composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY backend/ .

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

EXPOSE 80 6001

ENTRYPOINT ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
