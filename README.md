# Detect Unused Views on your Laravel Application

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

This will publish a file called `laravel-unused.php` in your `config` folder to adjust a few config values.

## Usage

You just need to visit `/laravelunused` if you didn't change `route_prefix` in your config (make sure debug mode is true)

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