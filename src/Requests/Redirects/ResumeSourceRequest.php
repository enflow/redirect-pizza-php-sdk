<?php

namespace RedirectPizza\PhpSdk\Requests\Redirects;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ResumeSourceRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected int $redirectId,
        protected int $sourceId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/redirects/{$this->redirectId}/sources/{$this->sourceId}/resume";
    }
}
