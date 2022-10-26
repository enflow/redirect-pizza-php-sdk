<?php

namespace RedirectPizza\PhpSdk\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RedirectPizza\PhpSdk\Exceptions\NotFoundException;
use RedirectPizza\PhpSdk\Exceptions\ValidationException;
use RedirectPizza\PhpSdk\RedirectPizza;

class RedirectPizzaSdkTest extends TestCase
{
    private ClientInterface|MockObject $guzzleClient;
    private RedirectPizza $redirectPizzaClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guzzleClient = $this->createMock(Client::class);
        $this->redirectPizzaClient = new RedirectPizza('123', $this->guzzleClient);
    }

    public function test_it_can_instantiate_an_object()
    {
        $sdk = new RedirectPizza('api-token');

        $this->assertTrue(is_object($sdk));
    }

    public function test_making_basic_requests()
    {
        $response = new Response(200, [], json_encode(['data' => [['id' => 1]]]));

        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $this->assertCount(1, $this->redirectPizzaClient->redirects());
    }

    public function test_handling_validation_errors()
    {
        $response = new Response(422, [], json_encode(['name' => ['The destination is required.']]));

        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->willReturn($response);

        try {
            $this->redirectPizzaClient->createRedirect([]);
        } catch (ValidationException $e) {
        }

        $this->assertEquals(['name' => ['The destination is required.']], $e->errors());
    }

    public function test_handling_404_errors()
    {
        $this->expectException(NotFoundException::class);

        $response = new Response(404, [], json_encode([]));

        $this->guzzleClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', 'redirects/123')
            ->willReturn($response);

        $this->redirectPizzaClient->redirect(123);
    }
}
