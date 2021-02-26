FROM php:7.4-fpm

WORKDIR /var/www

RUN docker-php-ext-install pdo pdo_mysql

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY . /var/www

COPY --chown=www:www . /var/www

USER www

EXPOSE 9000
CMD ["php-fpm"]