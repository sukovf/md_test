FROM php:8.3.12-cli

RUN apt update -y && apt upgrade -y && \
    apt install curl git zip bash -y

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin
RUN chmod +x /usr/local/bin/install-php-extensions

RUN install-php-extensions \
    xdebug

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh
ENTRYPOINT ["entrypoint.sh"]