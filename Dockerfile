# Use the richarvey/nginx-php-fpm base image
FROM richarvey/nginx-php-fpm:3.1.6

# Copy the entire Laravel project into the container
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Set environment variables for Laravel
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

# Install Composer
RUN apk add --no-cache composer \
    && composer install --no-dev --optimize-autoloader

# Expose port 80
EXPOSE 80

# Start the PHP-FPM service
CMD ["/start.sh"]