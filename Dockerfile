FROM php:7.2

RUN apt-get update && apt-get install -y libssl-dev zlib1g-dev

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# zip
RUN docker-php-ext-install zip

COPY . /app

WORKDIR /app

RUN composer install --prefer-dist --optimize-autoloader

RUN vendor/bin/phpcs --standard=vendor/escapestudios/symfony2-coding-standard/Symfony/ruleset.xml src

RUN vendor/bin/phpunit

CMD php -S 0.0.0.0:80 -t /app/public
