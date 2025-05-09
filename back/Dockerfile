FROM php:8.2-fpm-alpine

# Installer les dépendances nécessaires
RUN apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    zip \
    unzip \
    curl \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev

# Configuration PHP
RUN docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath opcache

# Configurer Nginx et le répertoire de travail
WORKDIR /var/www

# Copier les fichiers du projet
COPY . /var/www/

# Installer Composer et les dépendances
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --optimize-autoloader --no-dev

# Configuration Nginx
RUN mkdir -p /run/nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Configuration Supervisord
COPY docker/supervisord.conf /etc/supervisor.d/supervisord.conf

# Optimisation Laravel
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan storage:link && \
    chmod -R 777 /var/www/storage

# Exposer le port 80
EXPOSE 80

# Démarrer Supervisord
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor.d/supervisord.conf"]