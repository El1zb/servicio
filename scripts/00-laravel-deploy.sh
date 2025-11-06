#!/usr/bin/env bash

echo "🚀 Iniciando despliegue de Laravel..."

# Instalar dependencias PHP
echo "📦 Instalando dependencias..."
composer install --no-dev --optimize-autoloader --working-dir=/var/www/html

# Cachear configuración y rutas
echo "⚙️ Optimizando Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones (si hay conexión a base de datos)
echo "🧩 Ejecutando migraciones..."
php artisan migrate --force || true

# Publicar archivos de Cloudinary (opcional)
if php artisan vendor:publish --provider="CloudinaryLabs\CloudinaryLaravel\CloudinaryServiceProvider" --tag="cloudinary-laravel-config" &>/dev/null; then
    echo "☁️ Cloudinary configurado correctamente."
fi

echo "✅ Despliegue de Laravel completado."
