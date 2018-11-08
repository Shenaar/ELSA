FROM php

# Install selected extensions and other stuff
RUN apt-get update

RUN apt-get install -y libzip-dev libmcrypt-dev libmagickwand-dev imagemagick \
    mysql-client --no-install-recommends

RUN docker-php-ext-install pdo_mysql

RUN pecl install imagick && docker-php-ext-enable imagick
RUN pecl install zip && docker-php-ext-enable zip
RUN pecl install redis && docker-php-ext-enable redis

