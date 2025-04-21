#!/bin/bash

# Выполняем миграции, кешируем конфиги и т.д.
echo "🔧 Выполняем artisan команды..."

php artisan config:cache
php artisan route:cache
php artisan view:cache


echo "✅ Laravel готов. Запускаем php-fpm..."
exec php-fpm
