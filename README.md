# Example backend API Development
Example backend that consumes external api.

## System requirements

- PHP 7.1+
- Linux system (recommended)
- Docker (recommended)
- Docker compose (recommended)

## Installation

1) Install dependencies:

```
php composer.phar install
```

2) Start php web server from root folder (not document root)

```
php -S localhost:8080 -t public/
```

## Docker

Run the application with docker do the following command from the project root folder `docker-compose up`

## Testing

Project has `unit` and `integration` tests

### Unit tests
In the `/test` folder u can find several tests run them with the following command from the project root folder `vendor/bin/phpunit`

### Integration tests

In the `/test/integration` folder u can find several tests execute them with the following command from the project root folder `./integration-test.sh`

## License
The [MIT](http://opensource.org/licenses/MIT "MIT") License (MIT).
