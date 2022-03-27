FROM composer AS depend

COPY composer.json /app
COPY composer.lock /app

RUN composer install

FROM php:apache-bullseye

RUN apt-get update \
    && apt-get -y install sudo \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN echo "www-data ALL=NOPASSWD: /usr/bin/docker" >> /etc/sudoers

RUN docker-php-ext-install mysqli

RUN a2enmod rewrite

COPY . /var/www/html
COPY --from=depend /app/vendor /var/www/html/vendor
COPY _config.sample.php /var/www/html/_config.php