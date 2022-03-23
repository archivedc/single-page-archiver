FROM php:apache

RUN apt-get update \
    && apt-get -y install sudo \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN echo "www-data ALL=NOPASSWD: /usr/bin/docker" >> /etc/sudoers

RUN a2enmod rewrite

COPY . /var/www/html
COPY _config.sample.php /var/www/html/_config.php