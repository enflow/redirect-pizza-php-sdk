<?php

namespace RedirectPizza\PhpSdk\Requests\Redirects;

use RedirectPizza\PhpSdk\Dto\Redirect;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class GetRedirectsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/redirects';
    }

    /** @return array<int, Redirect> */
    public function createDtoFromResponse(Response $response): array
    {
        return Redirect::collect($response->json('data') ?? []);
    }
}
