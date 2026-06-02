# syntax=docker/dockerfile:1.4

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN mkdir -p bootstrap/cache && composer install --no-dev --prefer-dist --no-scripts --no-progress --no-interaction
COPY . /app
RUN mkdir -p bootstrap/cache && composer dump-autoload --optimize

FROM node:20 AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . /app
RUN npm run build

FROM php:8.3-cli
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zlib1g-dev \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring zip exif bcmath \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
RUN mkdir -p bootstrap/cache
COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build
COPY docker-entrypoint.sh /var/www/html/docker-entrypoint.sh
RUN chmod +x /var/www/html/docker-entrypoint.sh

EXPOSE 8080
ENV PORT=8080
ENTRYPOINT ["/var/www/html/docker-entrypoint.sh"]
CMD ["php", "-S", "0.0.0.0:${PORT}", "-t", "public", "public/index.php"]
