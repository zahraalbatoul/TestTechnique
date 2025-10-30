# Build stage for composer dependencies
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader

# Runtime stage
FROM php:8.2-cli
WORKDIR /app

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev git unzip \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

# Copy application code
COPY . /app

# Copy vendor from build stage
COPY --from=vendor /app/vendor /app/vendor

# Ensure storage and bootstrap/cache are writable
RUN chmod -R ug+rwx storage bootstrap/cache || true

# Generate app key during container build if not present
RUN php artisan key:generate --force || true 

# Default command: run migrations (ignore failures) then start PHP built-in server
CMD sh -c "php artisan migrate --force || true; php artisan tenants:migrate --force --all || true; php -S 0.0.0.0:$PORT -t public"
