#!/bin/bash
# Set permissions to storage and bootstrap cache
sudo chmod -R 0777 /var/www/html/storage
sudo chmod -R 0777 /var/www/html/bootstrap/cache
#
cd /var/www/html
#
# Copy the .env config file from S3 bucket to web root
#
sudo aws s3 cp s3://rocketjar-config/staging/.env .
#
# Run composer
sudo /usr/local/bin/composer install --no-ansi --no-dev --no-suggest --no-interaction --no-progress --prefer-dist --no-scripts -d /var/www/html
#
# Run artisan commands
sudo /usr/bin/php /var/www/html/artisan migrate --force
sudo /usr/bin/php /var/www/html/artisan passport:keys --force

# Restart all supervisor process (laravel queue workers)
sudo /usr/bin/supervisorctl restart all

#
sudo chown -R www-data:www-data /var/www/html
#
