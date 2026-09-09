# Dockerfile de PRODUCCIÓN (Render). Distinto de Dockerfile.dev (ese es solo
# para el entorno local con Docker Compose). Render ya no soporta PHP como
# runtime nativo ("env: php" en render.yaml) — exige runtime: docker.
#
# Build en 2 etapas: 1) compila los assets de Vite con Node, 2) imagen final
# con PHP + el código + los assets ya compilados. Así la imagen final no
# carga con Node/npm, solo lo necesario para correr la app.

# ── Etapa 1: assets (Tailwind/Vite) ─────────────────────────────────────────
FROM node:20-alpine AS assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

# ── Etapa 2: PHP + app ───────────────────────────────────────────────────────
FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
        git unzip libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev libpq-dev libfreetype6-dev libjpeg62-turbo-dev \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip xml intl \
        && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
        && apt-get install -y --no-install-recommends libreoffice-writer \
        && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize --no-dev \
    && mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
