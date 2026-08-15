<?php

namespace RedirectPizza\PhpSdk\Requests\Domains;

use RedirectPizza\PhpSdk\Dto\Domain;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class CheckDomainDnsRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected int $domainId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/domains/{$this->domainId}/check-dns";
    }

    public function createDtoFromResponse(Response $response): Domain
    {
        return Domain::fromResponse($response->json('data'));
    }
}
