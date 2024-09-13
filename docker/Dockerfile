FROM php:8.3-fpm-alpine

# Установка системных зависимостей и расширений PHP
RUN apk --no-cache add git unzip zip libzip-dev curl autoconf g++ make \
    && docker-php-ext-install pdo pdo_mysql zip \
    && pecl install redis \
    && docker-php-ext-enable pdo_mysql zip redis \
    && apk del autoconf g++ make

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Настройка рабочей директории
WORKDIR /var/www/html