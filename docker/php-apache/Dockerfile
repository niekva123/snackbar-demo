FROM php:8.1-apache

WORKDIR /var/www/

RUN apt-get update \
    && apt-get install -y git unzip mariadb-client vim wget libsodium-dev npm \
    && curl -s https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite \
    && service apache2 restart \
    && mkdir /var/www/sessions/ \
    && chown 1000:1000 /var/www/sessions/ \
    && chmod 777 /var/www/sessions/

EXPOSE 80