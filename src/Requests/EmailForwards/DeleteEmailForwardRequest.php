<?php

namespace RedirectPizza\PhpSdk\Requests\EmailForwards;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteEmailForwardRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected int $emailForwardId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/email-forwards/{$this->emailForwardId}";
    }
}
