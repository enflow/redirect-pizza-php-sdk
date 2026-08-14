<?php

use RedirectPizza\PhpSdk\Dto\HitsTotal;
use RedirectPizza\PhpSdk\Dto\QrCode;
use RedirectPizza\PhpSdk\Dto\Redirect;
use RedirectPizza\PhpSdk\Dto\RedirectTestResult;
use RedirectPizza\PhpSdk\Dto\User;
use RedirectPizza\PhpSdk\Enums\AnalyticsDimension;
use RedirectPizza\PhpSdk\Exceptions\RedirectPizzaException;
use RedirectPizza\PhpSdk\Exceptions\ValidationException;
use RedirectPizza\PhpSdk\RedirectPizza;
use RedirectPizza\PhpSdk\Requests\Analytics\GetDimensionsRequest;
use RedirectPizza\PhpSdk\Requests\Analytics\GetHitsTotalRequest;
use RedirectPizza\PhpSdk\Requests\Redirects\CreateRedirectRequest;
use RedirectPizza\PhpSdk\Requests\Redirects\GetRedirectRequest;
use RedirectPizza\PhpSdk\Requests\Redirects\GetRedirectsRequest;
use RedirectPizza\PhpSdk\Requests\Redirects\PauseRedirectRequest;
use RedirectPizza\PhpSdk\Requests\Users\GetUsersRequest;
use RedirectPizza\PhpSdk\Requests\Utils\GenerateQrCodeRequest;
use RedirectPizza\PhpSdk\Requests\Utils\TestRedirectRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->redirectPizza = redirectPizzaMock();
});

afterEach(function () {
    MockClient::destroyGlobal();
});

it('can instantiate an object', function () {
    expect(new RedirectPizza('api-token'))->toBeInstanceOf(RedirectPizza::class);
});

it('can make basic requests', function () {
    MockClient::global([
        GetRedirectsRequest::class => MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'sources' => [],
                    'domains' => [],
                    'destination' => 'https://example.com',
                    'redirect_type' => 'permanent',
                    'keep_query_string' => false,
                    'uri_forwarding' => false,
                    'tracking' => true,
                    'paused' => false,
                    'tags' => [],
                    'notes' => null,
                ],
            ],
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
            ],
        ]),
    ]);

    $redirects = iterator_to_array($this->redirectPizza->redirects());

    expect($redirects)->toHaveCount(1)
        ->and($redirects[0])->toBeInstanceOf(Redirect::class)
        ->and($redirects[0]->id)->toBe(1)
        ->and($redirects[0]->destination)->toBe('https://example.com');
});

it('handles validation errors', function () {
    MockClient::global([
        CreateRedirectRequest::class => MockResponse::make([
            'message' => 'The given data was invalid.',
            'errors' => [
                'destination' => ['The destination is required.'],
            ],
        ], 422),
    ]);

    try {
        $this->redirectPizza->createRedirect([]);
        $this->fail('Expected ValidationException was not thrown.');
    } catch (ValidationException $e) {
        expect($e->hasErrorsForField('destination'))->toBeTrue()
            ->and($e->getErrorsForField('destination'))->toBe(['The destination is required.'])
            ->and($e->getMessage())->toContain('destination');
    }
});

it('handles not found errors', function () {
    MockClient::global([
        GetRedirectRequest::class => MockResponse::make([], 404),
    ]);

    $this->redirectPizza->redirect(123);
})->throws(RedirectPizzaException::class);

it('can get a single redirect', function () {
    MockClient::global([
        GetRedirectRequest::class => MockResponse::make([
            'data' => [
                'id' => 42,
                'sources' => [
                    ['id' => 1, 'url' => 'old.example.com', 'regex' => false, 'paused' => true],
                ],
                'domains' => [
                    [
                        'id' => 10,
                        'fqdn' => 'old.example.com',
                        'is_root_domain' => false,
                        'hsts' => true,
                        'prevent_foreign_embedding' => false,
                        'referrer_policy' => 'no-referrer-when-downgrade',
                        'settings' => ['waf' => ['status' => 'inherit']],
                        'dns' => ['verified' => true],
                        'ssl' => ['active' => true],
                    ],
                ],
                'destination' => 'https://new.example.com',
                'redirect_type' => 'permanent',
                'keep_query_string' => true,
                'uri_forwarding' => false,
                'tracking' => true,
                'paused' => true,
                'tags' => ['marketing'],
                'notes' => 'Legacy domain',
            ],
        ]),
    ]);

    $redirect = $this->redirectPizza->redirect(42);

    expect($redirect->id)->toBe(42)
        ->and($redirect->paused)->toBeTrue()
        ->and($redirect->sources[0]->paused)->toBeTrue()
        ->and($redirect->domains[0]->referrerPolicy)->toBe('no-referrer-when-downgrade')
        ->and($redirect->tags)->toBe(['marketing']);
});

it('can pause a redirect', function () {
    $mockClient = MockClient::global([
        PauseRedirectRequest::class => MockResponse::make('', 204),
    ]);

    expect($this->redirectPizza->pauseRedirect(42))->toBe($this->redirectPizza);
    $mockClient->assertSent(PauseRedirectRequest::class);
});

it('can get hits total', function () {
    MockClient::global([
        GetHitsTotalRequest::class => MockResponse::make([
            'data' => ['count' => 150],
            'filters' => ['start' => '2025-04-30', 'end' => '2025-05-20', 'query' => null],
        ]),
    ]);

    $hits = $this->redirectPizza->hitsTotal(start: '2025-04-30', end: '2025-05-20');

    expect($hits)->toBeInstanceOf(HitsTotal::class)
        ->and($hits->count)->toBe(150);
});

it('can get dimension analytics', function () {
    MockClient::global([
        GetDimensionsRequest::class => MockResponse::make([
            'data' => [
                ['key' => 'NL', 'count' => 10],
                ['key' => 'US', 'count' => 5],
            ],
            'meta' => [
                'current_page' => 1,
                'per_page' => 10,
                'from' => 1,
                'to' => 2,
            ],
        ]),
    ]);

    $points = iterator_to_array($this->redirectPizza->dimensions(AnalyticsDimension::Countries));

    expect($points)->toHaveCount(2)
        ->and($points[0]->key)->toBe('NL')
        ->and($points[0]->count)->toBe(10);
});

it('can list users', function () {
    MockClient::global([
        GetUsersRequest::class => MockResponse::make([
            'data' => [
                [
                    'id' => 1,
                    'email' => 'member@example.com',
                    'role' => 'member',
                    'status' => 'active',
                    'access_type' => 'all',
                    'tags' => [],
                    'created_at' => null,
                ],
            ],
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
            ],
        ]),
    ]);

    $users = iterator_to_array($this->redirectPizza->users());

    expect($users)->toHaveCount(1)
        ->and($users[0])->toBeInstanceOf(User::class)
        ->and($users[0]->email)->toBe('member@example.com');
});

it('can test a redirect', function () {
    MockClient::global([
        TestRedirectRequest::class => MockResponse::make([
            'url' => 'https://example.com',
            'status_code' => 301,
            'status' => 'redirecting',
            'redirect' => [
                'to' => 'https://destination.com',
                'via_redirect_pizza' => true,
                'hsts' => false,
                'prevent_foreign_embedding' => false,
                'removed_hops' => false,
            ],
            'headers' => ['Location' => 'https://destination.com'],
            'duration' => 0.12,
            'probe' => [
                'name' => 'New York (US)',
                'location' => 'ewr',
                'city' => 'New York',
                'country' => 'US',
                'continent' => 'North America',
            ],
            'explanation' => 'Redirected',
            'error' => null,
            'next_test_url' => 'https://redirect.pizza/api/v1/tester?url=https%3A%2F%2Fdestination.com',
        ]),
    ]);

    $result = $this->redirectPizza->testRedirect('https://example.com');

    expect($result)->toBeInstanceOf(RedirectTestResult::class)
        ->and($result->status)->toBe('redirecting')
        ->and($result->statusCode)->toBe(301)
        ->and($result->redirect['to'])->toBe('https://destination.com');
});

it('can generate a qr code', function () {
    MockClient::global([
        GenerateQrCodeRequest::class => MockResponse::make([
            'url' => 'https://example.com',
            'image' => 'data:image/png;base64,abc',
            'destination' => 'https://example.com',
            'filename' => 'qr-code-example-com',
        ]),
    ]);

    $qr = $this->redirectPizza->qrCode('https://example.com');

    expect($qr)->toBeInstanceOf(QrCode::class)
        ->and($qr->image)->toBe('data:image/png;base64,abc');
});
