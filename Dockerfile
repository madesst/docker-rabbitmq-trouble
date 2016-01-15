FROM composer/composer

RUN docker-php-ext-install zip bcmath mbstring
ENTRYPOINT ["/app/entrypoint.sh"]
