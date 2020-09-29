<?php

namespace RedirectPizza\PhpSdk\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Mockery;
use PHPUnit\Framework\TestCase;
use RedirectPizza\PhpSdk\Exceptions\NotFoundException;
use RedirectPizza\PhpSdk\Exceptions\ValidationException;
use RedirectPizza\PhpSdk\RedirectPizza;

class RedirectPizzaSdkTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_it_can_instantiate_an_object()
    {
        $sdk = new RedirectPizza('api-token');

        $this->assertTrue(is_object($sdk));
    }

    public function test_making_basic_requests()
    {
        $redirectPizza = new RedirectPizza('123', $http = Mockery::mock(Client::class));

        $http->shouldReceive('request')->once()->with('GET', 'redirects', [])->andReturn(
            $response = Mockery::mock(Response::class)
        );

        $response->shouldReceive('getStatusCode')->once()->andReturn(200);
        $response->shouldReceive('getBody')->once()->andReturn('{"data": [{"id": "1"}]}');

        $this->assertCount(1, $redirectPizza->redirects());
    }

    public function test_handling_validation_errors()
    {
        $redirectPizza = new RedirectPizza('123', $http = Mockery::mock(Client::class));

        $http->shouldReceive('request')->once()->with('POST', 'redirects', [])->andReturn(
            $response = Mockery::mock(Response::class)
        );

        $response->shouldReceive('getStatusCode')->andReturn(422);
        $response->shouldReceive('getBody')->once()->andReturn('{"name": ["The destination is required."]}');

        try {
            $redirectPizza->createRedirect([]);
        } catch (ValidationException $e) {
        }

        $this->assertEquals(['name' => ['The destination is required.']], $e->errors());
    }

    public function test_handling_404_errors()
    {
        $this->expectException(NotFoundException::class);

        $redirectPizza = new RedirectPizza('123', $http = Mockery::mock(Client::class));

        $http->shouldReceive('request')->once()->with('GET', 'redirects/123', [])->andReturn(
            $response = Mockery::mock(Response::class)
        );

        $response->shouldReceive('getStatusCode')->andReturn(404);

        $redirectPizza->redirect(123);
    }
}
