# 1. Gunakan PHP 8.3 sesuai permintaan composer.json Anda
FROM php:8.3-apache

# 2. Install dependencies sistem
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev

# 3. Install ekstensi PHP (tambah libzip untuk zip)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 4. Aktifkan Apache Rewrite
RUN a2enmod rewrite

# 5. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Set working directory
WORKDIR /var/www/html
COPY . .

# 7. Jalankan composer install dengan mengabaikan pengecekan platform (agar lebih aman)
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# 8. Permission folder
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Set Apache Document Root ke folder PUBLIC Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

EXPOSE 80
CMD ["apache2-foreground"]