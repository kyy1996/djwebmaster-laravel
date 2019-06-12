#!/bin/sh

if hash pecl 2>/dev/null; then
    pecl install igbinary redis xdebug
else
    if hash apt-get 2>/dev/null; then
        apt-get install php-igbinary php-redis php-xdebug php-bcmath php-mbstring
    fi
fi

composer install

./artisan key:generate
./artisan migrate:reset
./artisan migrate --force --seed
./artisan passport:install --force
