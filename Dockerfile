FROM php:8.2-apache  # or php:8.2-fpm if using nginx

# Install MySQLi extension
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Also install PDO MySQL if you use PDO elsewhere
RUN docker-php-ext-install pdo_mysql

COPY . /var/www/html/
