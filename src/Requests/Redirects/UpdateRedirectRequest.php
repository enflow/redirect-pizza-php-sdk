<?php

namespace RedirectPizza\PhpSdk\Requests\Redirects;

use RedirectPizza\PhpSdk\Dto\Redirect;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateRedirectRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        protected int $redirectId,
        protected array $data,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/redirects/{$this->redirectId}";
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
