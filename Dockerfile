FROM php:8.3-apache

# 1. Install dependencies sistem
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 2. FIX MPM ERROR: Matikan modul mpm_event dan aktifkan mpm_prefork secara manual
RUN a2dismod mpm_event && a2enmod mpm_prefork

# 3. Aktifkan Apache Rewrite
RUN a2enmod rewrite

# 4. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Set working directory & Copy Project
WORKDIR /var/www/html
COPY . .

# 6. Install library Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# 7. Set permission folder (Sangat Penting)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Set Apache Document Root ke folder PUBLIC
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 9. Tambahkan ServerName untuk menghindari warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

EXPOSE 80
CMD ["apache2-foreground"]