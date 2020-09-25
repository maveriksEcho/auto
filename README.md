##Init

cp .env.example .env

php artisan composer install

php artisan key:gererate

php artisan migrate

## Seed db with car brands and models
php artisan auto:update-data

## Run scheduler to update brand and models db every month
php artisan schedule:run

php artisan serve

##End points:

##Get all Auto from db
* GET: http://127.0.0.1:8000/api/v1/auto
 query params:
     - search
     - sort
     - direction
     - filters (array)
     - per_page
     - page

##Create auto in db
* POST: http://127.0.0.1:8000/api/v1/auto

##Update auto in db
* PUT: http://127.0.0.1:8000/api/v1/auto/{id}

##Destroy auto in db
* DELETE: http://127.0.0.1:8000/api/v1/auto/{id}

##Auto-complete car brand
* GET: http://127.0.0.1:8000/api/v1/search
    query params:
     - search (min 2 character)
