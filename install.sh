#!/bin/sh

# pecl install igbinary redis xdebug

composer install

./artisan key:generate
./artisan migrate
./artisan passport:install --force
./artisan db:seed
