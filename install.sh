#!/bin/sh

pecl install igbinary redis xdebug

./artisan key:generate
./artisan migrate
./artisan passport:install --force
./artisan db:seed
