# Website of WebmasterClub at Shanghai Dianji University

[https://www.djwebclub.com](https://www.djwebclub.com)

## Instruction for deployment

### Configure

Copy the *environment configuration*

```bash
cp .env.example .env
```

and add your *database connection* and *SMTP Server* info to it.

```bash
vim .env
```

### Install

Just run

```bash
./install.sh
```

It will execute the following instructions.

```bash
# 0. Install some dependencies
if hash pecl 2>/dev/null; then
   pecl install igbinary redis xdebug
else
   if hash apt-get 2>/dev/null; then
       apt-get install php-igbinary php-redis php-xdebug php-bcmath php-mbstring
   fi
fi

composer install

# 1. Generate the key for the application.
./artisan key:generate
# 2. Drop all existing tables in your specific database.
./artisan migrate:reset
# 2. Migrate the tables.
# 3. Fill up your database with init data.
./artisan migrate --force --seed
# 4. Install the Laravel Passport used to support OAuth Server.
./artisan passport:install --force
```


### Default User

|     Username      |   Mobile  | Password |    Role     | 
|:-----------------:|:---------:|:--------:|:-----------:|
| admin@admin.admin |18181818181|  123456  | Super Admin |

## Reinstall

Reinstall like an new application according to *Instruction for deployment*

Just run

```shell
./install.sh
```

## Uninstall

## Clean your database

```bash
./artisan migrate:reset
```

## Laravel

<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## License

The Website is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
