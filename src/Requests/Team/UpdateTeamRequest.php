<?php

namespace RedirectPizza\PhpSdk\Requests\Team;

use RedirectPizza\PhpSdk\Dto\Team;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateTeamRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        protected array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/team';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }

    public function createDtoFromResponse(Response $response): Team
    {
        return Team::fromResponse($response->json('data'));
    }
}
