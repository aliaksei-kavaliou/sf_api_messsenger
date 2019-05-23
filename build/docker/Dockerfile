FROM php:7.3.5-fpm-alpine3.9

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

COPY ./build/base /

WORKDIR /var/www/html

COPY ./composer.* /var/www/html/
RUN composer install --no-scripts

COPY ./ /var/www/html
RUN chown -R www-data:www-data /var/www/html 

RUN composer install
