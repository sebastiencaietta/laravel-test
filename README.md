#Shopworks Code Test

##Installation

Clone repository

`git clone -v git@github.com:sebastiencaietta/shopworks`

Install composer dependencies

`composer install -o`

Create a .env file based on the .env.example file
Update MySQL config

Generate a key and clear artisan cache

`php artisan key:generate`

`php artisan config:clear`

Seed the database

`php artisan migrate`

`php artisan db:seed`

Start a local server

`php artisan serve`

Navigate to x.x.x.x/shifts
