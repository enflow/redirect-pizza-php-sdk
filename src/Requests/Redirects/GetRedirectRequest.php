<?php

namespace RedirectPizza\PhpSdk\Requests\Redirects;

use RedirectPizza\PhpSdk\Dto\Redirect;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetRedirectRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected int $redirectId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/redirects/{$this->redirectId}";
    }

    public function createDtoFromResponse(Response $response): Redirect
    {
        return Redirect::fromResponse($response->json('data'));
    }
}
