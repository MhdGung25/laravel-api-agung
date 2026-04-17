FROM php:8.3-apache

# 1. Install dependencies sistem
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl libzip-dev && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 2. Fix Apache MPM (Cegah Crash) & Aktifkan Rewrite
RUN a2dismod mpm_event && a2enmod mpm_prefork && a2enmod rewrite

# 3. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set Working Directory & Copy File
WORKDIR /var/www/html
COPY . .

# 5. Install Library Laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# 6. Set Permission (Penting agar Laravel bisa nulis log/cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Konfigurasi Document Root ke folder PUBLIC
# Ini supaya yang diakses adalah index.php di dalam folder public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 8. Set Port Apache ke 8080 (Sesuai settingan Railway kamu)
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# 9. Tambahkan ServerName untuk menghindari warning Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# 10. Final Port & Jalankan Apache
EXPOSE 8080
CMD ["apache2-foreground"]