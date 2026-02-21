FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libicu-dev \
    && docker-php-ext-install pdo_mysql zip gd mbstring exif pcntl curl intl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN a2enmod rewrite headers

RUN sed -i 's|/var/www/html|/var/www/webroot|g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www

COPY api/composer.json api/composer.lock* ./
RUN composer config --global audit.block-insecure false && composer update --no-dev --optimize-autoloader

COPY api/ .

RUN chmod -R 755 tmp logs webroot

EXPOSE 80

CMD ["apache2-foreground"]
