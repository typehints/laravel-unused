# Detect Unused Views on your Laravel Application
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Quality Score](https://scrutinizer-ci.com/g/typehints/laravel-unused/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/typehints/laravel-unused/)
[![StyleCI](https://github.styleci.io/repos/246931421/shield?branch=master)](https://styleci.io/repos/246931421)

## Introduction
LaraveUnused provides a UI where you can preview unused views from scanning all the routes on your application.
It also allows you to see more data about used views like the action code where the view is being used /  the partials that are used on a specific view / the views that a route is triggering...

<p align="center"><img src="/demo.png?raw=true"></p>

## Installation

You can install the package via composer:

```bash
composer require typehints/laravel-unused
```

You have to publish the package configuration using:

```bash
php artisan vendor:publish --provider=TypeHints\\Unused\\ServiceProvider
```

This will publish `config/laravel-unused.php` and `vendor/laravel-unused`

## Usage

You just need to visit `/laravelunused` if you didn't change `route_prefix` in your config (make sure debug mode is true)

You can also add your own middelwares on `config/laravel-unused.php`

``` php
    'middleware' => [
        TypeHints\Unused\Middleware\LaravelUnusedMiddleware::class,
        // Custom Middleware
    ],
```

## Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email mohamed@typehints.com instead of using the issue tracker.

## Credits

- [Mohamed Benhida](https://github.com/simoebenhida)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.
