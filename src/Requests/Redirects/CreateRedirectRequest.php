<?php

namespace RedirectPizza\PhpSdk\Requests\Redirects;

use RedirectPizza\PhpSdk\Dto\Redirect;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateRedirectRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/redirects';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }

    public function createDtoFromResponse(Response $response): Redirect
    {
        return Redirect::fromResponse($response->json('data'));
    }
}
