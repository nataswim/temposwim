# Image de base avec PHP 8.3 et Apache
FROM php:8.3-apache

# Installation des dépendances PHP
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Paramètre d'environnement (test/prod)
ARG MODE=test

# Copie des fichiers
COPY . /var/www/app
WORKDIR /var/www/app
COPY .env.${MODE} /var/www/app/.env
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Configuration Apache
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/app \
    && chmod -R 775 /var/www/app/storage

# Installation de composer
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:2.6.5 /usr/bin/composer /usr/local/bin/composer

# Installation des dépendances
RUN composer install --optimize-autoloader --no-dev