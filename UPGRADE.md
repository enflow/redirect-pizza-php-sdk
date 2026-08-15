# Upgrade guide

## From 2.x to 3.x

Version 3 rebuilds the SDK on [Saloon](https://docs.saloon.dev) v4. This is a breaking release.

### Requirements

- PHP `^8.3` (unchanged)
- Replace the direct `guzzlehttp/guzzle` dependency with:
  - `saloonphp/saloon` `^4.0`
  - `saloonphp/pagination-plugin` `^2.2`

Composer will pull these in automatically when you upgrade the package.

### Instantiating the client

The optional Guzzle `Client` argument was removed. Use named arguments for `baseUrl` and `timeoutInSeconds` instead.

```diff
- $redirectPizza = new RedirectPizza($apiToken, $guzzleClient);
+ $redirectPizza = new RedirectPizza($apiToken);
+ $redirectPizza = new RedirectPizza($apiToken, timeoutInSeconds: 30);
```

`$apiToken` is no longer a public property on the client.

### Resources → DTOs

Classes under `RedirectPizza\PhpSdk\Resources` moved to `RedirectPizza\PhpSdk\Dto`.

| 2.x | 3.x |
| --- | --- |
| `Resources\Redirect` | `Dto\Redirect` |
| `Resources\Domain` | `Dto\Domain` |
| `Resources\EmailForward` | `Dto\EmailForward` |
| `Resources\Team` | `Dto\Team` |
| `Resources\Source` | `Dto\Source` |
| `Resources\ApiResource` | removed |

DTOs are plain data objects with constructor-promoted properties and `fromResponse()` / `collect()` helpers. They no longer hold a reference to the client.

Property names are unchanged (`$redirect->destination`, `$redirect->redirectType`, etc.). Nested `sources` and `domains` on a redirect are still typed objects (`Source` / `Domain`). `EmailForward::$domain` is now a nullable `Domain` DTO instead of an array.

### No more mutating methods on models

Update, delete, and DNS-check helpers were removed from DTOs. Call the matching methods on `RedirectPizza` instead.

```diff
- $redirect->update([...]);
- $redirect->delete();
- $domain->checkDns();
- $emailForward->update([...]);
- $emailForward->delete();
+ $redirectPizza->updateRedirect($redirect->id, [...]);
+ $redirectPizza->deleteRedirect($redirect->id);
+ $redirectPizza->checkDomainDns($domain->id);
+ $redirectPizza->updateEmailForward($emailForward->id, [...]);
+ $redirectPizza->deleteEmailForward($emailForward->id);
```

`deleteRedirect()` and `deleteEmailForward()` now return the client (`self`) instead of `void`.

### List methods return iterators

`redirects()`, `domains()`, and `emailForwards()` return a paginated `iterable` instead of an `array`. They automatically walk all pages.

```diff
- $redirects = $redirectPizza->redirects();
- $this->assertCount(1, $redirects);
+ $redirects = $redirectPizza->redirects();
+ foreach ($redirects as $redirect) {
+     // ...
+ }
+ // or materialize when you need an array:
+ $redirects = iterator_to_array($redirectPizza->redirects());
```

### Method rename

```diff
- $redirectPizza->EmailForward($id);
+ $redirectPizza->emailForward($id);
```

### Exceptions

| 2.x | 3.x |
| --- | --- |
| `ValidationException` | still exists; see API changes below |
| `ApiException` | `RedirectPizzaException` |
| `NotFoundException` | `RedirectPizzaException` |
| `UnauthorizedException` | `RedirectPizzaException` |
| `FailedActionException` | `RedirectPizzaException` |

`ValidationException` now wraps the Saloon response (Laravel-style `{ message, errors }`) and exposes:

- `getErrors()`
- `getErrorsForField(string $field)`
- `hasErrorsForField(string $field)`
- `getAllErrorMessages()`

```diff
- } catch (ValidationException $e) {
-     $e->errors();
+ } catch (ValidationException $e) {
+     $e->getErrors();
+     $e->getErrorsForField('destination');
  }
```

Catch `RedirectPizzaException` for all non-validation HTTP failures (including 404 / 401 / 400).

### Testing

Injecting a mocked Guzzle client is no longer supported. Prefer Saloon’s `MockClient`:

```php
use RedirectPizza\PhpSdk\RedirectPizza;
use RedirectPizza\PhpSdk\Requests\Redirects\GetRedirectsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

MockClient::global([
    GetRedirectsRequest::class => MockResponse::make([
        'data' => [/* ... */],
        'meta' => ['current_page' => 1, 'last_page' => 1],
    ]),
]);

$redirectPizza = new RedirectPizza('fake-token');
$redirects = iterator_to_array($redirectPizza->redirects());
```

### Quick migration checklist

1. Upgrade the package and run Composer so Saloon is installed.
2. Update imports from `Resources\` to `Dto\`.
3. Replace `$model->update()` / `delete()` / `checkDns()` with client methods.
4. Treat list results as iterables (or wrap with `iterator_to_array()`).
5. Rename `EmailForward()` → `emailForward()`.
6. Switch exception catches to `ValidationException` / `RedirectPizzaException` and use `getErrors()`.
7. Replace Guzzle test doubles with Saloon `MockClient`.
