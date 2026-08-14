<?php

namespace RedirectPizza\PhpSdk\Requests\Analytics;

use RedirectPizza\PhpSdk\Dto\HitsTotal;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetHitsTotalRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $start = null,
        protected ?string $end = null,
        protected ?string $queryString = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/analytics/hits';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'start' => $this->start,
            'end' => $this->end,
            'query' => $this->queryString,
        ], fn ($value) => $value !== null);
    }

    public function createDtoFromResponse(Response $response): HitsTotal
    {
        return HitsTotal::fromResponse($response->json());
    }
}
