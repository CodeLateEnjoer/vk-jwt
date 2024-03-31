FROM php:8.1-fpm

RUN docker-php-ext-install pdo pdo_mysql

COPY ./ /var/www/html/
WORKDIR /var/www/html/

RUN apt-get update

CMD ["php-fpm"]
