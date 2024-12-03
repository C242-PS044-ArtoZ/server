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
