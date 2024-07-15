FROM php:8.0-fpm

RUN apt-get update && apt-get install -y \
        libzip-dev \
        zip \
        && pecl install mongodb@1.9.0 \
        && mongodb-clients \
        && docker-php-ext-enable mongodb \
        && docker-php-ext-install pdo \
        && docker-php-ext-install pdo_mysql \
        && docker-php-ext-install zip \
        && docker-php-ext-install opcache

WORKDIR /var/www/html

COPY ./ /var/www/html

RUN chown -R www-data:www-data /var/www/html

CMD ["php-fpm"]