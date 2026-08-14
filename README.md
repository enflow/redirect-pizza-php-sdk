# SDK to easily work with the redirect.pizza API

[![redirect.pizza](https://redirect.pizza/favicons/open-graph.png)](https://redirect.pizza?ref=github)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/enflow/redirect-pizza-php-sdk.svg?style=flat-square)](https://packagist.org/packages/enflow/redirect-pizza-php-sdk)
![GitHub Workflow Status](https://github.com/enflow/redirect-pizza-php-sdk/workflows/run-tests/badge.svg)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/enflow/redirect-pizza-php-sdk.svg?style=flat-square)](https://packagist.org/packages/enflow/redirect-pizza-php-sdk)

This package is the official PHP SDK for the redirect.pizza API, built with Saloon v4.

```php
use RedirectPizza\PhpSdk\RedirectPizza;

$redirectPizza = new RedirectPizza('your-api-token');

$redirect = $redirectPizza->createRedirect([
    'sources' => ['old-source.nl'],
    'destination' => 'new-fancy-site.nl',
    'redirect_type' => 'permanent',
    'keep_query_string' => false,
]);

// returns an iterator of RedirectPizza\PhpSdk\Dto\Redirect
$redirects = $redirectPizza->redirects();

foreach ($redirects as $redirect) {
    echo "Redirect: {$redirect->destination} (ID: {$redirect->id})\n";
}
```

Behind the scenes, the SDK uses [Saloon](https://docs.saloon.dev) to make the HTTP requests.

## Installation

```composer require enflow/redirect-pizza-php-sdk```

Upgrading from 2.x? See [UPGRADE.md](UPGRADE.md).

## Usage

```php
use RedirectPizza\PhpSdk\RedirectPizza;

$redirectPizza = new RedirectPizza('rpa_XXXXXXXXXXXXXXXXXXX'); // https://redirect.pizza/api
```

### Setting a timeout

By default, the SDK will wait for a response for 10 seconds. You can change this by passing a `timeoutInSeconds` option to the constructor:

```php
$redirectPizza = new RedirectPizza('your-api-token', timeoutInSeconds: 30);
```

### Handling errors

The SDK will throw an exception if the API returns an error. For validation errors, the SDK will throw a `ValidationException`.

```php
try {
    $redirectPizza->createRedirect([
        'destination' => 'invalid',
    ]);
} catch (\RedirectPizza\PhpSdk\Exceptions\ValidationException $exception) {
    $exception->getMessage(); // string describing the errors
    $exception->getErrors(); // array with all validation errors
}
```

For all other errors, the SDK will throw a `\RedirectPizza\PhpSdk\Exceptions\RedirectPizzaException`.

### Redirects

```php
// returns an iterator of RedirectPizza\PhpSdk\Dto\Redirect
$redirects = $redirectPizza->redirects();

// Optional search/filter query (status:active, tag:marketing, source:..., destination:...)
$redirects = $redirectPizza->redirects('status:active tag:marketing');

$redirect = $redirectPizza->createRedirect([
    'sources' => ['old-source.nl'],
    'destination' => 'new-fancy-site.nl',
    'redirect_type' => 'permanent',
    'keep_query_string' => false,
]);

$redirect = $redirectPizza->redirect($redirectId);

$redirect = $redirectPizza->updateRedirect($redirectId, [
    'sources' => ['old-source.nl'],
    'destination' => 'new-fancy-site-v2.nl',
    'redirect_type' => 'permanent',
    'keep_query_string' => true,
]);

$redirectPizza->pauseRedirect($redirectId);
$redirectPizza->resumeRedirect($redirectId);
$redirectPizza->pauseSource($redirectId, $sourceId);
$redirectPizza->resumeSource($redirectId, $sourceId);

$redirectPizza->deleteRedirect($redirectId);
```

### Domains

```php
$domains = $redirectPizza->domains();

$domain = $redirectPizza->domain($domainId);

$domain = $redirectPizza->updateDomain($domainId, [
    'hsts' => ['status' => 'enabled', 'max_age' => 31536000],
    'waf' => ['status' => 'inherit'],
]);

$domain = $redirectPizza->checkDomainDns($domainId);

$result = $redirectPizza->applyAutomaticDns($domainId);
// $result->successful, $result->output

$redirectPizza->deleteDomain($domainId);
```

### Email forwards

```php
$emailForwards = $redirectPizza->emailForwards();

$emailForward = $redirectPizza->emailForward($emailForwardId);

$emailForward = $redirectPizza->createEmailForward([
    'domain_id' => 1,
    'alias' => 'hello',
    'destination' => 'you@example.com',
]);

$emailForward = $redirectPizza->updateEmailForward($emailForwardId, [
    'destination' => 'new@example.com',
]);

$redirectPizza->deleteEmailForward($emailForwardId);
```

### Analytics (beta)

```php
use RedirectPizza\PhpSdk\Enums\AnalyticsDimension;

$hits = $redirectPizza->hitsTotal(start: '2025-04-30', end: '2025-05-20');
echo $hits->count;

$series = $redirectPizza->timeSeries(start: '2025-04-30', end: '2025-05-20');

$dimensions = $redirectPizza->dimensions(
    AnalyticsDimension::Countries,
    start: '2025-04-30',
    end: '2025-05-20',
);

$rawHits = $redirectPizza->rawHits(start: '2025-04-30', end: '2025-05-20', query: 'redirect:123');
```

### Team

```php
$team = $redirectPizza->team();

$team = $redirectPizza->updateTeam([
    'name' => 'Acme Inc',
    'summary_frequency' => 'weekly',
]);
```

### Users

```php
$users = $redirectPizza->users();

$user = $redirectPizza->inviteUser([
    'email' => 'colleague@example.com',
    'role' => 'member',
    'access_type' => 'all',
]);

$user = $redirectPizza->updateUser($userId, [
    'role' => 'readonly',
    'access_type' => 'allowed',
    'tags' => ['marketing'],
]);

$redirectPizza->deleteUser($userId);
```

### Utils

These endpoints do not require authentication.

```php
$result = $redirectPizza->testRedirect('https://example.com');
echo $result->status; // success, redirecting, upgrading, error

$qr = $redirectPizza->qrCode('https://example.com'); // JSON metadata + base64 image
// or binary: $response = $redirectPizza->qrCode('https://example.com', format: 'png');
```

## Security

If you discover any security related issues, please email support@redirect.pizza instead of using the issue tracker.

## Credits

- [Michel Bardelmeijer](https://github.com/mbardelmeijer)
- [All Contributors](../../contributors)

This package is greatly inspired by the [Oh Dear PHP SDK](https://github.com/ohdearapp/ohdear-php-sdk) by [Freek van der Herten](https://github.com/freekmurze) and [Mattias Geniar](https://github.com/mattiasgeniar).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
