# Telegram Bot #


## Installation ##
0. `sudo chgrp -R www-data storage bootstrap/cache` -- only for Linux
0. `sudo chmod -R ug+rwx storage bootstrap/cache` -- only for Linux
0. `composer install`
0. `cp .env.example .env`
0. `php artisan key:generate`
0. `nano .env`  -- configure env (pusher keys are required)
0. `php artisan migrate --seed`
0. `php artisan passport:install`

## To Do ##

Make this after each git pull or changes in environment

```
php composer install && 
php artisan cache:clear &&  
php artisan migrate --seed
```
