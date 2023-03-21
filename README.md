# Delivery API

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sashalenz/delivery-auto-api.svg?style=flat-square)](https://packagist.org/packages/sashalenz/delivery-auto-api)
[![Total Downloads](https://img.shields.io/packagist/dt/sashalenz/delivery-auto-api.svg?style=flat-square)](https://packagist.org/packages/sashalenz/delivery-auto-api)


This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require sashalenz/delivery-auto-api
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Sashalenz\DeliveryAuto\DeliveryServiceProvider" --tag="delivery-api-migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Sashalenz\DeliveryAuto\DeliveryServiceProvider" --tag="delivery-api-config"
```

This is the contents of the published config file:

```php
return [
    'url' => env('DELIVERY_API_URL', 'https://delivery-auto.com/api/'),

    'public_key' => env('DELIVERY_API_PUBLIC_KEY', null),
    'secret_key' => env('DELIVERY_API_SECRET_KEY', null),
    
    'culture' => env('DELIVERY_API_CULTURE', 'RU_ru')
];
```

## Usage

```php
$delivery = new Sashalenz\DeliveryAuto();
echo $delivery->echoPhrase('Hello, sashalenz!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Oleksandr Petrovskyi](https://github.com/sashalenz)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
