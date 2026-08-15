<?php

namespace RedirectPizza\PhpSdk\Requests\Redirects;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteRedirectRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected int $redirectId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/redirects/{$this->redirectId}";
    }
}
