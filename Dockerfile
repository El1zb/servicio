# Dockerfile de PRODUCCIÓN (Render). Distinto de Dockerfile.dev (ese es solo
# para el entorno local con Docker Compose). Render ya no soporta PHP como
# runtime nativo ("env: php" en render.yaml) — exige runtime: docker.
#
# Build en 3 etapas:
#   1) vendor  — dependencias de Composer (se necesitan ANTES de compilar los
#      assets: app.css importa directo vendor/livewire/flux/dist/flux.css).
#   2) assets  — compila Tailwind/Vite con Node.
#   3) imagen final — PHP + el código + vendor + assets ya compilados. No
#      carga con Node/npm, solo lo necesario para correr la app.

# ── Etapa 1: dependencias de Composer ────────────────────────────────────────
FROM composer:2 AS vendor

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --ignore-platform-reqs --prefer-dist

# ── Etapa 2: assets (Tailwind/Vite) ─────────────────────────────────────────
FROM node:20-alpine AS assets

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY --from=vendor /app/vendor ./vendor
COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

# ── Etapa 3: PHP + app ───────────────────────────────────────────────────────
FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
        git unzip libzip-dev libpng-dev libonig-dev libxml2-dev libicu-dev libpq-dev libfreetype6-dev libjpeg62-turbo-dev \
        && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd zip xml intl \
        && apt-get install -y --no-install-recommends libreoffice-writer \
        && rm -rf /var/lib/apt/lists/*

COPY --from=vendor /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

COPY --from=vendor /app/vendor ./vendor
COPY . .
COPY --from=assets /app/public/build ./public/build

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && composer dump-autoload --optimize --no-dev

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
