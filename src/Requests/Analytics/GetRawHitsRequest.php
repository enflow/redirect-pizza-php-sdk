<?php

namespace RedirectPizza\PhpSdk\Requests\Analytics;

use RedirectPizza\PhpSdk\Dto\RawHit;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class GetRawHitsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $start = null,
        protected ?string $end = null,
        protected ?string $queryString = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/analytics/raw';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'start' => $this->start,
            'end' => $this->end,
            'query' => $this->queryString,
        ], fn ($value) => $value !== null);
    }

    /** @return array<int, RawHit> */
    public function createDtoFromResponse(Response $response): array
    {
        return RawHit::collect($response->json('data') ?? []);
    }
}
