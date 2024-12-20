## Requirements

* docker >= 20
* docker-compose >= 1.29
* php >= 8.3
* laravel >= 11
* mysql >= 8.0.15
* composer >= 2.0.7

## Installation

1. `sudo nano /etc/hosts`
2. `127.0.0.1 tasks-api.com`
3. `git clone `
4. `cd tasks`
5. `cp .env.example .env`
6. `docker build`
7. `docker up`

## In other bash console execute
8. `docker exec -it tasks_api_php bash`
9. `composer install`
10. `php artisan migrate --seed`


The API has a different endpoint for managing tasks.

