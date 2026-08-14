<?php

namespace RedirectPizza\PhpSdk\Tests;

use PHPUnit\Framework\TestCase;
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

class RedirectPizzaSdkTest extends TestCase
{
    private RedirectPizza $redirectPizza;

    protected function setUp(): void
    {
        parent::setUp();

        MockClient::destroyGlobal();

        $this->redirectPizza = new RedirectPizza('fake-api-token');
    }

    protected function tearDown(): void
    {
        MockClient::destroyGlobal();

        parent::tearDown();
    }

    public function test_it_can_instantiate_an_object(): void
    {
        $this->assertInstanceOf(RedirectPizza::class, new RedirectPizza('api-token'));
    }

    public function test_making_basic_requests(): void
    {
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

        $this->assertCount(1, $redirects);
        $this->assertInstanceOf(Redirect::class, $redirects[0]);
        $this->assertSame(1, $redirects[0]->id);
        $this->assertSame('https://example.com', $redirects[0]->destination);
    }

    public function test_handling_validation_errors(): void
    {
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
            $this->assertTrue($e->hasErrorsForField('destination'));
            $this->assertSame(['The destination is required.'], $e->getErrorsForField('destination'));
            $this->assertStringContainsString('destination', $e->getMessage());
        }
    }

    public function test_handling_not_found_errors(): void
    {
        $this->expectException(RedirectPizzaException::class);

        MockClient::global([
            GetRedirectRequest::class => MockResponse::make([], 404),
        ]);

        $this->redirectPizza->redirect(123);
    }

    public function test_it_can_get_a_single_redirect(): void
    {
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

        $this->assertSame(42, $redirect->id);
        $this->assertTrue($redirect->paused);
        $this->assertTrue($redirect->sources[0]->paused);
        $this->assertSame('no-referrer-when-downgrade', $redirect->domains[0]->referrerPolicy);
        $this->assertSame(['marketing'], $redirect->tags);
    }

    public function test_it_can_pause_a_redirect(): void
    {
        $mockClient = MockClient::global([
            PauseRedirectRequest::class => MockResponse::make('', 204),
        ]);

        $this->assertSame($this->redirectPizza, $this->redirectPizza->pauseRedirect(42));
        $mockClient->assertSent(PauseRedirectRequest::class);
    }

    public function test_it_can_get_hits_total(): void
    {
        MockClient::global([
            GetHitsTotalRequest::class => MockResponse::make([
                'data' => ['count' => 150],
                'filters' => ['start' => '2025-04-30', 'end' => '2025-05-20', 'query' => null],
            ]),
        ]);

        $hits = $this->redirectPizza->hitsTotal(start: '2025-04-30', end: '2025-05-20');

        $this->assertInstanceOf(HitsTotal::class, $hits);
        $this->assertSame(150, $hits->count);
    }

    public function test_it_can_get_dimension_analytics(): void
    {
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

        $this->assertCount(2, $points);
        $this->assertSame('NL', $points[0]->key);
        $this->assertSame(10, $points[0]->count);
    }

    public function test_it_can_list_users(): void
    {
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

        $this->assertCount(1, $users);
        $this->assertInstanceOf(User::class, $users[0]);
        $this->assertSame('member@example.com', $users[0]->email);
    }

    public function test_it_can_test_a_redirect(): void
    {
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

        $this->assertInstanceOf(RedirectTestResult::class, $result);
        $this->assertSame('redirecting', $result->status);
        $this->assertSame(301, $result->statusCode);
        $this->assertSame('https://destination.com', $result->redirect['to']);
    }

    public function test_it_can_generate_a_qr_code(): void
    {
        MockClient::global([
            GenerateQrCodeRequest::class => MockResponse::make([
                'url' => 'https://example.com',
                'image' => 'data:image/png;base64,abc',
                'destination' => 'https://example.com',
                'filename' => 'qr-code-example-com',
            ]),
        ]);

        $qr = $this->redirectPizza->qrCode('https://example.com');

        $this->assertInstanceOf(QrCode::class, $qr);
        $this->assertSame('data:image/png;base64,abc', $qr->image);
    }
}
