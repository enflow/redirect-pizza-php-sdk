<?php

namespace RedirectPizza\PhpSdk\Requests\Users;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteUserRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected int $userId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/users/{$this->userId}";
    }
}
