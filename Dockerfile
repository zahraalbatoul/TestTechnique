# Stage 1: PHP dependencies (no scripts)
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-scripts

# Stage 2: Frontend assets build
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --no-audit --no-fund
COPY resources ./resources
COPY vite.config.js ./
COPY postcss.config.js ./
COPY tailwind.config.js ./
RUN npm run build

# Stage 3: Runtime
FROM php:8.2-cli
WORKDIR /app

# System deps & PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev git unzip \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Copy app code
COPY . /app
# Copy vendor from composer stage
COPY --from=vendor /app/vendor /app/vendor
# Copy built assets to public/build
COPY --from=assets /app/public/build /app/public/build

# Permissions
RUN chmod -R ug+rwx storage bootstrap/cache || true

# Finalize app (ignore failures in build-time context)
RUN php artisan key:generate --force || true \
 && php artisan package:discover --ansi || true

# Run migrations on start, then serve app
ENV PORT=8080
CMD sh -c "php artisan migrate --force || true; php artisan tenants:migrate --force --all || true; php -S 0.0.0.0:$PORT -t public"
