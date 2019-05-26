FROM php:7.2-fpm-alpine

RUN apk update \
    && apk add  --no-cache bash git mysql-client curl libmcrypt libmcrypt-dev openssh-client icu-dev \
    libxml2-dev freetype-dev libpng-dev libjpeg-turbo-dev g++ make autoconf \
    && docker-php-source extract \
    && pecl install xdebug redis \
    && docker-php-ext-enable xdebug redis \
    && docker-php-source delete \
    && docker-php-ext-install pdo_mysql soap intl zip \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_port=10000" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_connect_back=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && rm -rf /tmp/*

ENV APP_ENV='dev' \
    MYSQL_ROOT_PASSWORD=password \
    MYSQL_DATABASE=app_db \
    MYSQL_PASSWORD=password \
    MYSQL_USER=app_user \
    DATABASE_URL=fake \
    APP_DEBUG=1 \
    AWS_KEY=fake \
    AWS_SECRET=fake \
    AWS_SQS_ENDPOINT=fake \
    AWS_REGION=fake \
    AWS_S3_BUCKET=fake \
    MESSENGER_SQS_QUEUE=fake

#Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN echo 'alias composer_d="php -d memory_limit=-1 /usr/local/bin/composer"' >> ~/.bashrc && source ~/.bashrc

WORKDIR /var/www/html

COPY ./composer.* /var/www/html/
RUN composer install --no-scripts

COPY ./ /var/www/html
RUN chown -R www-data:www-data /var/www/html

RUN composer install

CMD ["php-fpm", "-F"]

EXPOSE 9000
