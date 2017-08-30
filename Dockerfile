FROM php:7.1-alpine

WORKDIR /var/www/html
ADD . /var/www/html

#RUN pecl install xdebug

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --filename=composer --install-dir=/usr/local/bin \
    && composer global require "hirak/prestissimo:^0.3" && composer install

CMD ["php", "bin/console", "server:start", "0.0.0.0:80"]
