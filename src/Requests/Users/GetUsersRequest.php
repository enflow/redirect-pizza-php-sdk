<?php

namespace RedirectPizza\PhpSdk\Requests\Users;

use RedirectPizza\PhpSdk\Dto\User;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class GetUsersRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/users';
    }

    /** @return array<int, User> */
    public function createDtoFromResponse(Response $response): array
    {
        return User::collect($response->json('data') ?? []);
    }
}
