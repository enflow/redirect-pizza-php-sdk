# An SDK to easily work with API from redirect.pizza

[![Latest Version on Packagist](https://img.shields.io/packagist/v/enflow/redirect-pizza-php-sdk.svg?style=flat-square)](https://packagist.org/packages/enflow/redirect-pizza-php-sdk)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/enflow/redirect-pizza-php-sdk.svg?style=flat-square)](https://packagist.org/packages/enflow/redirect-pizza-php-sdk)

## Installation

```composer require enflow/redirect-pizza-php-sdk```

## Usage

```php
$apiToken = ''; // You can find this token in your "API" page on https://redirect.pizza

$redirectPizza = new \RedirectPizza\PhpSdk\RedirectPizza($apiToken);

// List all redirects
$redirectPizza->redirects();

// Create redirect
$redirect = $redirectPizza->createRedirect([
    'sources' => ['old-source.nl'],
    'destination' => 'new-fancy-site.nl',
    'redirect_type' => 'permanent',
    'keep_query_string' => false,
]);

// Fetch redirect
$redirectPizza->redirect($redirect->id);

// Update redirect
$redirect->update([
    'sources' => ['old-source.nl'],
    'destination' => 'new-fancy-site-v2.nl',
    'redirect_type' => 'permanent',
    'keep_query_string' => true,
]);

// Delete the redirect
$redirect->delete();
```

## Security

If you discover any security related issues, please email support@redirect.pizza instead of using the issue tracker.

## Credits

- [Michel Bardelmeijer](https://github.com/mbardelmeijer)
- [All Contributors](../../contributors)

This package uses code from and is greatly inspired by the [OhDear PHP SDK](https://github.com/ohdearapp/ohdear-php-sdk) by [Freek van der Herten](https://github.com/freekmurze) and [Mattias Geniar](https://github.com/mattiasgeniar), which is based on [Forge SDK package](https://github.com/themsaid/forge-sdk) by [Mohammed Said](https://github.com/themsaid).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
