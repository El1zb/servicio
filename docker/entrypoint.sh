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

exec php artisan serve --host 0.0.0.0 --port "${PORT:-10000}"
