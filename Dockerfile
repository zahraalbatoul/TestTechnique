# Stage 1: PHP deps (no scripts)
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-scripts

# Stage 2: build assets
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY resources ./resources
COPY vite.config.js postcss.config.js tailwind.config.js ./
RUN npm run build

# Stage 3: runtime
FROM php:8.2-cli
WORKDIR /app
RUN apt-get update \
 && apt-get install -y --no-install-recommends libpq-dev git unzip \
 && docker-php-ext-install pdo pdo_pgsql \
 && rm -rf /var/lib/apt/lists/*

COPY . /app
COPY --from=vendor /app/vendor /app/vendor
COPY --from=assets /app/public/build /app/public/build

RUN chmod -R ug+rwx storage bootstrap/cache || true
RUN php artisan key:generate --force || true && php artisan package:discover --ansi || true

ENV PORT=8080
CMD sh -c "php artisan migrate --force || true; php artisan tenants:migrate --force --all || true; php -S 0.0.0.0:$PORT -t public"
