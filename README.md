### After cloning repository run these commands.
````
composer install
````
````
copy .env.example .env
````
````
set DB_DATABASE, DB_USERNAME and DB_PASSWORD in .env file
````
````
set mail configs in .env file
````
````
php artisan migrate
````
````
php artisan db:seed
````
````
user email - liam@gmail.com
user password - Password1234
````
````
To send notifications you should run

php artisan schedule:work
php artisan queue:listen
````
````
npm install
````
````
npm run dev
````
