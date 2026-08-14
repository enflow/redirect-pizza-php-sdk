<?php

namespace RedirectPizza\PhpSdk\Requests\Domains;

use RedirectPizza\PhpSdk\Dto\AutomaticDnsResult;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class ApplyAutomaticDnsRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        protected int $domainId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/domains/{$this->domainId}/automatic-dns";
    }

    public function createDtoFromResponse(Response $response): AutomaticDnsResult
    {
        return AutomaticDnsResult::fromResponse($response->json());
    }
}
