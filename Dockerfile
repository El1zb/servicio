# ----------------------------
# Etapa 1: Construcción de assets (Tailwind, Vite)
# ----------------------------
FROM node:20-alpine AS build

# Crear y entrar a la carpeta del proyecto
WORKDIR /app

# Copiar archivos necesarios para npm
COPY package*.json vite.config.* tailwind.config.* postcss.config.* ./

# Instalar dependencias de Node
RUN npm install

# Copiar el resto del proyecto (incluye vendor y recursos)
COPY . .

# ⚙️ Instalar PHP 8.3 y Composer temporalmente para generar /vendor
RUN apk add --no-cache \
    php83 php83-phar php83-openssl php83-mbstring php83-tokenizer php83-xml \
    php83-dom php83-fileinfo php83-curl php83-session php83-gd php83-simplexml \
    php83-xmlreader php83-xmlwriter php83-zip php83-bcmath php83-pdo php83-pdo_mysql php83-iconv \
    composer bash

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Compilar assets de Tailwind / Vite para producción
RUN npm run build


# ----------------------------
# Etapa 2: Servidor Laravel (PHP 8.3-FPM + Nginx)
# ----------------------------
FROM php:8.3-fpm-alpine

# Instalar Nginx y utilidades básicas
RUN apk add --no-cache nginx supervisor bash curl

# Variables de entorno básicas
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copiar la app desde la etapa de build
COPY --from=build /app /var/www/html

# Dar permisos correctos a storage y bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copiar script de arranque
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Exponer puerto 80
EXPOSE 80

# Comando para iniciar PHP-FPM + Nginx usando start.sh
CMD ["/start.sh"]
