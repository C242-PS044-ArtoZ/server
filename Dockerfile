FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    curl \
    libonig-dev \
    libzip-dev \
    nginx \
    && docker-php-ext-install pdo_mysql mbstring

# Copy Nginx configuration
COPY nginx.conf /etc/nginx/sites-available/default

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-dev

# Clear cache
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose port
EXPOSE 8080

# Start Nginx and PHP-FPM
CMD ["sh", "-c", "nginx && php-fpm"]
