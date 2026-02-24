FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    curl \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libicu-dev \
    libxslt-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif bcmath gd intl xsl xml zip \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Laravel app code
COPY . .

# Copy .env.example to .env (for environment configuration)
# RUN cp .env.example .env

# run composer update
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Generate app key
RUN php artisan key:generate

# Migrate database
RUN php artisan migrate

# Copy nginx and supervisor config
COPY ./docker/nginx.conf /etc/nginx/sites-available/default
COPY ./docker/supervisord.conf /etc/supervisord.conf


# Set permissions for the Laravel app
RUN find . -type f -exec chmod 644 {} \; \
    && find . -type d -exec chmod 755 {} \; \
    && chown -R www-data:www-data . \
    && chmod -R 755 . \
    && chmod -R 777 storage \
    && chmod -R 777 storage/* \
    && chmod -R 777 bootstrap/cache

EXPOSE 8000

# Start the application using supervisord to manage nginx and php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
