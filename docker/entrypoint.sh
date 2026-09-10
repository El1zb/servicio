#!/bin/sh
# Arranque del contenedor en Render: migra siempre (idempotente — Laravel
# solo aplica lo pendiente), pero solo siembra datos de ejemplo la primera
# vez (base de datos recién creada), para no duplicar registros cada vez
# que el free tier de Render reinicia el contenedor por inactividad.
set -e

php artisan migrate --force

USER_COUNT=$(php artisan tinker --execute="echo \App\Models\User::count();" 2>/dev/null | tail -1)
if [ "$USER_COUNT" = "0" ]; then
    php artisan db:seed --force
fi

# Cachea config/vistas compiladas — se corre en cada arranque del
# contenedor (no en el build) porque las env vars reales de Render solo
# existen en runtime. Sin esto, Laravel re-lee todos los archivos de
# config en cada petición.
#
# Sin route:cache (route:cache/optimize completo): routes/web.php tiene
# rutas con Closure ('/' y 'periods/{id}') — route:cache falla duro con
# esas, y con "set -e" tumbaría el arranque del contenedor.
php artisan config:cache
php artisan view:cache

exec php artisan serve --host 0.0.0.0 --port "${PORT:-10000}"
