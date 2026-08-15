<?php

namespace RedirectPizza\PhpSdk\Requests\EmailForwards;

use RedirectPizza\PhpSdk\Dto\EmailForward;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class GetEmailForwardsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/email-forwards';
    }

    /** @return array<int, EmailForward> */
    public function createDtoFromResponse(Response $response): array
    {
        return EmailForward::collect($response->json('data') ?? []);
    }
}
