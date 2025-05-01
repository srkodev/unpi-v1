#!/bin/bash

# Script pour mettre à jour les liens et la configuration Apache dans Docker

# Se positionner dans le répertoire du script
cd "$(dirname "$0")"

echo "=== Mise à jour des liens du site pour Docker ==="

# 1. Exécution du script PHP pour mettre à jour les liens
echo "Exécution du script de mise à jour des liens..."
php update-links.php

# 1.1. Exécution du script pour corriger les problèmes d'inclusion
echo "Correction des problèmes d'inclusion..."
php fix-includes.php

# 1.2 Correction spécifique pour mentions-legales.php
echo "Correction spécifique pour mentions-legales.php..."
php fix-mentions-legales.php

# 2. Mise à jour du Dockerfile pour configurer Apache correctement
echo "Mise à jour du Dockerfile.web..."
cat > Dockerfile.web << 'EOF'
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set Apache document root to public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update the default apache site configuration
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Create virtual host configuration
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot ${APACHE_DOCUMENT_ROOT}\n\
    ServerName localhost\n\
    <Directory ${APACHE_DOCUMENT_ROOT}>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Create the public directory if it doesn't exist
RUN mkdir -p /var/www/html/public

# Change ownership of our applications
RUN chown -R www-data:www-data /var/www/html
EOF

echo "Mise à jour des volumes dans docker-compose.yml..."
sed -i 's|^      - ./docker-config.php:/var/www/html/app/config/config.php|      - ./docker-config.php:/var/www/html/app/config/config.php\n      - ./site-url.php:/var/www/html/public/site-url.php|g' docker-compose.yml

# 3. Création d'un fichier PHP pour définir la constante de URL du site
echo "Création du fichier site-url.php..."
cat > site-url.php << 'EOF'
<?php
/**
 * Définition de la constante d'URL du site pour l'environnement Docker
 * Ce fichier sera inclus dans l'application
 */

// URL de base du site (à modifier selon l'environnement)
if (!defined('SITE_URL')) {
    define('SITE_URL', '/');
}
EOF

echo "=== Configuration terminée ==="
echo "Vous pouvez maintenant reconstruire les conteneurs Docker avec:"
echo "docker-compose -f script/docker-compose.yml down"
echo "docker-compose -f script/docker-compose.yml up -d --build" 