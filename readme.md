# CO2 Application

This is a test application based on Symfony (https://symfony.com/). 

## Requirements
- MySql Server
- php min in Version 8.1
- installed version of Symfony Local Web Server (https://symfony.com/doc/current/setup/symfony_server.html) OR Apache WebServer

## Install

- clone repository
- enter root dir of cloned repository
- open .env file in root directory with editor and configure your database connection at DATABASE_URL  
- run ``php ./tools/composer.phar dev-setup``
- run ``php ./bin/console doctrine:database:create``
- run ``php ./bin/console doctrine:migration:migrate``
- if you have installed Symfony local web server run ``symfony serve`` otherwise use your prefer solution to run your server with that code

## API Doc

After successfully install and starting the server you can open the url ``http://127.0.0.1:8000/api/doc`` there you will find the documentation for the api.

## Execute tests

- run ``php ./tools/composer.phar test``

## Open Tasks

- Add more unit test
- Add integration test
- Add PHPMD (Is currently not compatible with PHP 8.1 enums)
- Add Composer unused checks

## Available Scripts

There are a few scripts prepared to increase the code quality and support developers. Take a look into composer.json in section scripts.

