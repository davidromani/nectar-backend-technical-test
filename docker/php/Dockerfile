FROM php:8.3-apache

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN apt-get update \
    && apt-get install -qq -y --no-install-recommends \
     nmap \
     cron \
     sqlite3 \
     vim \
     locales coreutils apt-utils git libicu-dev g++ libsqlite3-dev libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev;

RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "es_ES.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen

RUN a2enmod rewrite
RUN a2enmod headers
RUN a2enmod ssl

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_sqlite pdo_mysql mysqli gd opcache intl zip calendar dom mbstring zip gd xsl && a2enmod rewrite
RUN pecl install apcu && docker-php-ext-enable apcu

COPY ./ /var/www/project
WORKDIR /var/www/project

EXPOSE 80
EXPOSE 443
