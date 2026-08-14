<?php

namespace RedirectPizza\PhpSdk\Requests\Team;

use RedirectPizza\PhpSdk\Dto\Team;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetTeamRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/team';
    }

    public function createDtoFromResponse(Response $response): Team
    {
        return Team::fromResponse($response->json('data'));
    }
}
