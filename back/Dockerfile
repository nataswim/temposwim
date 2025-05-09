FROM php:8.2-fpm-alpine

# Installation des dépendances
RUN apk add --no-cache \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    nginx \
    supervisor

# Extensions PHP
RUN docker-php-ext-install pdo pdo_mysql zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copier les fichiers du projet
COPY . /var/www

# Installation des dépendances
RUN composer install --no-dev --optimize-autoloader

# Optimisation Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Configuration Nginx
RUN mkdir -p /etc/nginx/conf.d
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Configuration Supervisord
RUN mkdir -p /etc/supervisor/conf.d
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]