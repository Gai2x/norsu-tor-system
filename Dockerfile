FROM php:8.2-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite if needed
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
