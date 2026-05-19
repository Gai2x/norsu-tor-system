#!/bin/bash

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
for i in {1..30}; do
  if mysql -h"$MYSQL_HOST" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "SELECT 1" &> /dev/null; then
    echo "MySQL is ready!"
    break
  fi
  echo "Attempt $i/30 - MySQL not ready yet, waiting..."
  sleep 1
done

# Check if database exists
if ! mysql -h"$MYSQL_HOST" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "USE $MYSQL_DATABASE" &> /dev/null; then
  echo "Creating database and importing schema..."
  mysql -h"$MYSQL_HOST" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "CREATE DATABASE IF NOT EXISTS $MYSQL_DATABASE"
  mysql -h"$MYSQL_HOST" -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE" < /var/www/html/database/norsu_system.sql
  echo "Database initialized successfully!"
else
  echo "Database already exists, skipping initialization"
fi

# Start Apache
exec apache2-foreground
