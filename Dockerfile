FROM php:8.2-apache

# Habilitar módulos de Apache
RUN a2enmod rewrite headers
RUN a2enmod proxy_http

# Instalar extensiones PHP
RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libonig-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd zip pdo pdo_mysql xml curl opcache

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Clonar el repositorio de Laravel
RUN git clone https://github.com/Luisa-mk/Backend-hotelcomfii.git /var/www/html

RUN git config --global --add safe.directory /var/www/html
WORKDIR /var/www/html
RUN git checkout master
RUN git pull
RUN composer install

# Instalar Xdebug
RUN pecl install xdebug-3.3.2 \
    && docker-php-ext-enable xdebug

# Configuración Xdebug
RUN echo 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.client_port=9003' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Copiar laravel.conf
COPY laravel.conf /etc/apache2/sites-available/laravel.conf

# Desactivar el default site
RUN a2dissite 000-default.conf

# Habilitar laravel.conf
RUN a2ensite laravel.conf

# Permisos necesarios
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]