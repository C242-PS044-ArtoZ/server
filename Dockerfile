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

# Copy application files while respecting .dockerignore
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-dev

# Ensure storage and cache directories have correct permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Clear cache
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Copy and set permissions for start.sh
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Expose port
EXPOSE 8080

# Use start.sh to start services
CMD ["/usr/local/bin/start.sh"]
