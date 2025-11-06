#!/usr/bin/env bash

echo "🚀 Iniciando configuración de Laravel..."

# Instalar dependencias de Composer (modo producción)
composer install --no-dev --optimize-autoloader

# Cachear configuración y rutas
echo "⚙️ Cacheando configuración y rutas..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones (SQLite / desarrollo: ignora tablas existentes)
echo "📦 Ejecutando migraciones..."
php artisan migrate:fresh --force

# Iniciar Nginx y PHP-FPM
echo "✅ Iniciando servidor Nginx + PHP-FPM..."
nginx -g "daemon off;" &
php-fpm
