#!/bin/bash

# Start PHP-FPM
echo "Starting PHP-FPM..."
php-fpm &

# Start NGINX
echo "Starting NGINX..."
nginx -g "daemon off;"
