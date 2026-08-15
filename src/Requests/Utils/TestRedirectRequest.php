<?php

namespace RedirectPizza\PhpSdk\Requests\Utils;

use RedirectPizza\PhpSdk\Dto\RedirectTestResult;
use Saloon\Enums\Method;
use Saloon\Http\Auth\NullAuthenticator;
use Saloon\Http\Request;
use Saloon\Http\Response;

class TestRedirectRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $url,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/tester';
    }

    protected function defaultAuth(): NullAuthenticator
    {
        return new NullAuthenticator;
    }

    protected function defaultQuery(): array
    {
        return [
            'url' => $this->url,
        ];
    }

    public function createDtoFromResponse(Response $response): RedirectTestResult
    {
        return RedirectTestResult::fromResponse($response->json());
    }
}
