<?php

namespace RedirectPizza\PhpSdk\Requests\Users;

use RedirectPizza\PhpSdk\Dto\User;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateUserRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        protected int $userId,
        protected array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/users/{$this->userId}";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }

    public function createDtoFromResponse(Response $response): User
    {
        return User::fromResponse($response->json('data'));
    }
}
