FROM php:8.2-apache

# Disable conflicting MPM modules - keep only mpm_prefork for mod_php
RUN a2dismod mpm_event mpm_worker || true

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
