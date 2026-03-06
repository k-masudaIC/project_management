FROM php:8.4-fpm

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/src

COPY ./src/composer.json ./src/composer.lock ./

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-scripts --no-autoloader

COPY ./src .

RUN composer dump-autoload --optimize --no-scripts \
    && php artisan package:discover --ansi || true

RUN chown -R www-data:www-data /var/www

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/entrypoint.sh"]