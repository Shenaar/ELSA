FROM php:7.4

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /temp

RUN apt-get update && apt-get install -y libzip-dev libzip-dev libmcrypt-dev imagemagick libmagickwand-dev --no-install-recommends \
    && usermod -u 1000 www-data && groupmod -g 1000 www-data \
	&& docker-php-ext-install bcmath pdo_mysql zip

RUN pecl install -o -f redis imagick \
        && docker-php-ext-enable redis imagick \
        && docker-php-source delete

RUN mkdir -p /temp \
    && php -r "copy('https://getcomposer.org/installer', '/temp/composer-setup.php');" \
    && php /temp/composer-setup.php --no-ansi --install-dir=/usr/bin --filename=composer  \
    && rm -rf /temp/composer-setup.php /temp/.htaccess \
    && chmod a+rwx /temp -R && chgrp 1000 /temp \
    && rm -rf /var/cache/apt /var/cache/debconf /var/lib/apt \
    && mkdir -p /var/www/.config/psysh && chmod a+rwx /var/www/.config/psysh -R
