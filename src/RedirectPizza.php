<?php

namespace RedirectPizza\PhpSdk;

use GuzzleHttp\Client;
use RedirectPizza\PhpSdk\Actions\ManagesRedirects;

class RedirectPizza
{
    use MakesHttpRequests,
        ManagesRedirects;

    /** @var string */
    public $apiToken;

    /** @var \GuzzleHttp\Client */
    public $client;

    public function __construct(string $apiToken, Client $client = null)
    {
        $this->apiToken = $apiToken;

        $this->client = $client ?: new Client([
            'base_uri' => 'https://redirect.pizza/api/v1/',
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiToken,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    protected function transformCollection(array $collection, string $class): array
    {
        return array_map(function ($attributes) use ($class) {
            return new $class($attributes, $this);
        }, $collection);
    }
}
