<?php

namespace RedirectPizza\PhpSdk\Tests;

use PHPUnit\Framework\TestCase;
use RedirectPizza\PhpSdk\Dto\Redirect;
use RedirectPizza\PhpSdk\Exceptions\RedirectPizzaException;
use RedirectPizza\PhpSdk\Exceptions\ValidationException;
use RedirectPizza\PhpSdk\RedirectPizza;
use RedirectPizza\PhpSdk\Requests\Redirects\CreateRedirectRequest;
use RedirectPizza\PhpSdk\Requests\Redirects\GetRedirectRequest;
use RedirectPizza\PhpSdk\Requests\Redirects\GetRedirectsRequest;
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
                        ['id' => 1, 'url' => 'old.example.com'],
                    ],
                    'domains' => [
                        [
                            'id' => 10,
                            'fqdn' => 'old.example.com',
                            'is_root_domain' => false,
                            'hsts' => true,
                            'prevent_foreign_embedding' => false,
                            'dns' => ['verified' => true],
                            'ssl' => ['active' => true],
                        ],
                    ],
                    'destination' => 'https://new.example.com',
                    'redirect_type' => 'permanent',
                    'keep_query_string' => true,
                    'uri_forwarding' => false,
                    'tracking' => true,
                    'tags' => ['marketing'],
                    'notes' => 'Legacy domain',
                ],
            ]),
        ]);

        $redirect = $this->redirectPizza->redirect(42);

        $this->assertSame(42, $redirect->id);
        $this->assertSame('https://new.example.com', $redirect->destination);
        $this->assertSame('old.example.com', $redirect->sources[0]->url);
        $this->assertSame('old.example.com', $redirect->domains[0]->fqdn);
        $this->assertSame(['marketing'], $redirect->tags);
    }
}
