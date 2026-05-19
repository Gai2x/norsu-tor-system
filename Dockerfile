FROM php:8.2-apache

# CRITICAL: Disable conflicting MPM modules FIRST
# The base image has mpm_prefork, mpm_event, and mpm_worker all enabled
# Apache only allows ONE MPM - we keep mpm_prefork for mod_php
RUN a2dismod mpm_event mpm_worker

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Enable Apache mod_rewrite (now safe - no MPM conflicts)
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
