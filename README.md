# An SDK to easily work with API from redirect.pizza

## Installation

```composer require enflow/redirect-pizza-php-sdk```

## Usage

```
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
