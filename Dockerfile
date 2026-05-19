FROM php:8.2-apache

# Fix MPM conflict before installing anything
RUN a2dismod mpm_worker mpm_event 2>/dev/null || true
RUN a2enmod mpm_prefork

# Now install MySQLi
RUN docker-php-ext-install mysqli pdo_mysql

# Copy your app
COPY . /var/www/html/

# Ensure correct permissions
RUN chown -R www-data:www-data /var/www/html
