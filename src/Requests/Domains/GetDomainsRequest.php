<?php

namespace RedirectPizza\PhpSdk\Requests\Domains;

use RedirectPizza\PhpSdk\Dto\Domain;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class GetDomainsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $queryString = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/domains';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'query' => $this->queryString,
        ], fn ($value) => $value !== null);
    }

    /** @return array<int, Domain> */
    public function createDtoFromResponse(Response $response): array
    {
        return Domain::collect($response->json('data') ?? []);
    }
}
