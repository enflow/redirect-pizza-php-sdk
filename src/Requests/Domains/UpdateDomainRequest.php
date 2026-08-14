<?php

namespace RedirectPizza\PhpSdk\Requests\Domains;

use RedirectPizza\PhpSdk\Dto\Domain;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateDomainRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PATCH;

    public function __construct(
        protected int $domainId,
        protected array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/domains/{$this->domainId}";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }

    public function createDtoFromResponse(Response $response): Domain
    {
        return Domain::fromResponse($response->json('data'));
    }
}
