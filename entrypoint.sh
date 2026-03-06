#!/bin/bash
set -e

cd /var/www/src

if [ ! -f "vendor/autoload.php" ]; then
    echo "Installing composer dependencies..."
    COMPOSER_ALLOW_SUPERUSER=1 composer install --no-scripts --no-autoloader
    COMPOSER_ALLOW_SUPERUSER=1 composer dump-autoload --optimize --no-scripts
fi

exec php artisan serve --host=0.0.0.0 --port=8000
