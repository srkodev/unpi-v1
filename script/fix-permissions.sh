#!/bin/bash
# Script to fix permissions in the Docker container

# This command should be run inside the container or
# executed using docker exec

# Make uploads directory writable
mkdir -p /var/www/html/public/uploads/partenaires
mkdir -p /var/www/html/public/uploads/biens
mkdir -p /var/www/html/public/uploads/actualites

# Set ownership to www-data (Apache user)
chown -R www-data:www-data /var/www/html/public/uploads

# Set permissions
chmod -R 777 /var/www/html/public/uploads

echo "Permissions fixed for upload directories."
echo "Directories created and permissions set to 777."
echo "Owner set to www-data:www-data."

# Display current permissions
echo -e "\nCurrent permissions:"
ls -la /var/www/html/public/uploads
ls -la /var/www/html/public/uploads/partenaires
ls -la /var/www/html/public/uploads/biens
ls -la /var/www/html/public/uploads/actualites 