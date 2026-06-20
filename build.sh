#!/bin/bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

# Start Apache in foreground
exec apache2-foreground
