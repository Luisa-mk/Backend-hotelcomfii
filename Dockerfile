# Se utiliza una imagen de PHP 8.2 con apache
FROM php:8.2-apache

# Habilitar los modulos de apache
RUN a2enmod rewrite headers
RUN a2enmod proxy_http

# Instalar las extensiones de PHP necesarias
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

# Habilitar composer allow superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

# Clonar el repositorio de Laravel por HTTPS (porque es público)
RUN git clone https://github.com/Luisa-mk/Backend-hotelcomfii.git /var/www/html

RUN git config --global --add safe.directory /var/www/html
WORKDIR /var/www/html
RUN git checkout master
RUN git pull
RUN composer install

# Instalar Xdebug 3.3.2
RUN pecl install xdebug-3.3.2 \
    && docker-php-ext-enable xdebug

# Configurar Xdebug
RUN echo 'xdebug.mode=debug' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.client_port=9003' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo 'xdebug.start_with_request=yes' >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Copiar configuración de Apache para Laravel
COPY laravel.conf /etc/apache2/sites-available/laravel.conf

# Habilitar el sitio de Laravel
RUN a2ensite laravel.conf

# Exponer el puerto 80
EXPOSE 80

# Iniciar Apache en primer plano (en lugar de php artisan serve)
CMD ["apache2-foreground"]