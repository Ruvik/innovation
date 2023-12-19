FROM php:8.1-fpm-alpine

# Встановлення залежностей
RUN apk update && apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Встановлення розширень PHP
RUN docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Встановлення залежностей для компіляції PHP-розширень
RUN apk add --no-cache autoconf g++ make

RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && apk add --update linux-headers \
    && pecl install xdebug-3.2.2 \
    && docker-php-ext-enable xdebug \
    && apk del -f .build-deps

# Копіювання власних налаштувань PHP
COPY ./docker/php-fpm/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini


# Встановлення Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
