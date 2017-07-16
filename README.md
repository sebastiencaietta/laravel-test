# Laravel Test

## Installation

Clone repository

`git clone -v git@github.com:sebastiencaietta/laravel-test`

Install composer dependencies

`composer install -o`

Create a .env file based on the .env.example file

Update MySQL config

### Installation with phing

If you need to migrate and seed the database

`vendor/bin/phing -f build.xml install`

Otherwise

`vendor/bin/phing -f build.xml install -DwithDb=false`

Navigate to 127.0.0.1:8000/shifts

### Manual installation

Generate a key and clear artisan cache

`php artisan key:generate`

`php artisan config:clear`

Seed the database

`php artisan migrate`

`php artisan db:seed`

Start a local server

`php artisan serve`

Navigate to x.x.x.x/shifts
