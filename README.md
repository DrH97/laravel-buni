# Buni API Library

[![GitHub Tests Workflow](https://github.com/DrH97/laravel-buni/actions/workflows/test.yml/badge.svg?branch=master)](https://github.com/DrH97/laravel-buni/actions/workflows/test.yml)
[![Github Style Workflow](https://github.com/DrH97/laravel-buni/actions/workflows/styleci.yml/badge.svg?branch=master)](https://github.com/DrH97/laravel-buni/actions/workflows/styleci.yml)
[![codecov](https://codecov.io/gh/DrH97/laravel-buni/branch/main/graph/badge.svg?token=6b0d0ba1-c2c6-4077-8c3a-1f567eea88a0)](https://codecov.io/gh/DrH97/laravel-buni)

[![Latest Stable Version](http://poser.pugx.org/drh/laravel-buni/v)](https://packagist.org/packages/drh/laravel-buni)
[![Total Downloads](http://poser.pugx.org/drh/laravel-buni/downloads)](https://packagist.org/packages/drh/laravel-buni)
[![License](http://poser.pugx.org/drh/laravel-buni/license)](https://packagist.org/packages/drh/laravel-buni)
[![PHP Version Require](http://poser.pugx.org/drh/laravel-buni/require/php)](https://packagist.org/packages/drh/laravel-buni)

## Installation

You can install the package via composer:

```bash
composer require drh/laravel-buni
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-buni-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-buni-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$buni = new DrH\buni();
echo $buni->echoPhrase('Hello, DrH!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Dr H](https://github.com/DrH97)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
