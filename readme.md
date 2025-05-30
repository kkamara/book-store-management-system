<img src="https://github.com/kkamara/useful/blob/main/book-store-management-system7.png?raw=true" alt="book-store-management-system7.png" width=""/>

<img src="https://github.com/kkamara/useful/blob/main/book-store-management-system.png?raw=true" alt="book-store-management-system.png" width=""/>

<img src="https://github.com/kkamara/useful/blob/main/book-store-management-system2.png?raw=true" alt="book-store-management-system2.png" width=""/>

<img src="https://github.com/kkamara/useful/blob/main/book-store-management-system3.png?raw=true" alt="book-store-management-system3.png" width=""/>

<img src="https://github.com/kkamara/useful/blob/main/book-store-management-system4.png?raw=true" alt="book-store-management-system4.png" width=""/>

<img src="https://github.com/kkamara/useful/blob/main/book-store-management-system5.png?raw=true" alt="book-store-management-system5.png" width=""/>

<img src="https://github.com/kkamara/useful/blob/main/book-store-management-system6.png?raw=true" alt="book-store-management-system6.png" width=""/>

# Book Store Management System [![API](https://github.com/kkamara/book-store-management-system/actions/workflows/build.yml/badge.svg)](https://github.com/kkamara/book-store-management-system/actions/workflows/build.yml)

(14-Oct-2024) www.1000projects.org challenge. Made with Laravel 11, ReactJS 18 and Filament. This project has admin to insert books or a list of books, cart, orders, categories, and reviews. With tests.

* [Using Postman?](#postman)

* [Installation](#installation)

* [Usage](#usage)

* [API Documentation](#api-documentation)

* [Unit Tests](#unit-tests)

* [Misc.](#misc)

* [Contributing](#contributing)

* [License](#license)

<a name="postman"></a>
## Using Postman?

[Get Postman HTTP client](https://www.postman.com/).

[Postman API Collection for Book Store Management System](https://github.com/kkamara/book-store-management-system/blob/main/database/book-store-management-system.postman_collection.json).

[Postman API Environment for Book Store Management System](https://github.com/kkamara/book-store-management-system/blob/main/database/book-store-management-system.postman_environment.json).

## Installation

* [Laravel Herd](https://herd.laravel.com)
* [MySQL (recommended) or database engine of SQLite, MariaDB, PostgreSQL, SQL Server](https://laravel.com/docs/11.x/database#introduction)
* [https://laravel.com/docs/11.x/installation](https://laravel.com/docs/11.x/installation)
* [https://laravel.com/docs/11.x/vite#main-content](https://laravel.com/docs/11.x/vite#main-content)

```powershell
# Create our environment file.
cp .env.example .env
# Update database values in .env file.
# Install our app dependencies.
composer i
php artisan key:generate
# Before running the next command:
# Update your database details in .env
# Note that the following path is fixed for Powershell usage.
php artisan migrate --path=database\migrations\v1 --seed
npm install
npm run build
```

## Usage

```bash
herd link book
# Website accessible at http://book.test
```

## API Documentation

```bash
php artisan route:list
# output
...
POST       api/user ............................ login › V1\API\UserController@login
GET|HEAD   api/user/authorize .................. V1\API\UserController@authorizeUser
POST       api/user/register ................... V1\API\UserController@register
...
```

View the api collection [here](https://documenter.getpostman.com/view/17125932/TzzAKvVe).

## Unit Tests

```bash
php artisan test --filter=V1
```

View the unit test code [here](https://raw.githubusercontent.com/kkamara/php-reactjs-boilerplate/main/tests/Unit/Api/UsersTest.php).

## Misc.

[See PHP ReactJS Boilerplate app](https://github.com/kkamara/php-reactjs-boilerplate)

[See Python ReactJS Boilerplate app](https://github.com/kkamara/python-reactjs-boilerplate)

[See MRVL Desktop](https://github.com/kkamara/mrvl-desktop)

[See MRVL Web](https://github.com/kkamara/mrvl-web)

[See Github to Bitbucket Backup Repo Updater](https://github.com/kkamara/ghbbupdater)

[See PHP Docker Skeleton](https://github.com/kkamara/php-docker-skeleton)

[See Python Docker Skeleton](https://github.com/kkamara/python-docker-skeleton)

[See Laravel 10 API 3](https://github.com/kkamara/laravel-10-api-3)

[See movies app](https://github.com/kkamara/movies)

[See Food Nutrition Facts Search web app](https://github.com/kkamara/food-nutrition-facts-search-web-app)

[See Ecommerce Web](https://github.com/kkamara/ecommerce-web)

[See City Maps Mobile](https://github.com/kkamara/city-maps-mobile)

[See Ecommerce Mobile](https://github.com/kkamara/ecommerce-mobile)

[See CRM](https://github.com/kkamara/crm)

[See Birthday Currency](https://github.com/kkamara/birthday-currency)

[See PHP Scraper](https://github.com/kkamara/php-scraper).

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[BSD](https://opensource.org/licenses/BSD-3-Clause)
