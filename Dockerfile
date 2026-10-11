FROM php:8.4-apache

RUN docker-php-ext-install pdo_mysql mysqli

RUN a2enmod rewrite

RUN printf "<Directory /var/www/html>\nAllowOverride All\nRequire all granted\n</Directory>\n" \
    > /etc/apache2/conf-available/jijou.conf \
    && a2enconf jijou