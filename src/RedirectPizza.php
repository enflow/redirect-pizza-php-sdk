<?php

namespace RedirectPizza\PhpSdk;

use GuzzleHttp\Client;
use RedirectPizza\PhpSdk\Actions\ManagesDomains;
use RedirectPizza\PhpSdk\Actions\ManagesEmailForwards;
use RedirectPizza\PhpSdk\Actions\ManagesRedirects;
use RedirectPizza\PhpSdk\Actions\ManagesTeam;

class RedirectPizza
{
    use MakesHttpRequests;
    use ManagesRedirects;
    use ManagesEmailForwards;
    use ManagesDomains;
    use ManagesTeam;

    public function __construct(public string $apiToken, protected ?Client $client = null)
    {
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
        return array_map(fn ($attributes) => new $class($attributes, $this), $collection);
    }
}
