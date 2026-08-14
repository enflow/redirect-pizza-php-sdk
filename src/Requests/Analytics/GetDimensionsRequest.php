<?php

namespace RedirectPizza\PhpSdk\Requests\Analytics;

use RedirectPizza\PhpSdk\Dto\AnalyticsDataPoint;
use RedirectPizza\PhpSdk\Enums\AnalyticsDimension;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\Paginatable;

class GetDimensionsRequest extends Request implements Paginatable
{
    protected Method $method = Method::GET;

    public function __construct(
        protected AnalyticsDimension|string $dimension,
        protected ?string $start = null,
        protected ?string $end = null,
        protected ?string $queryString = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        $dimension = $this->dimension instanceof AnalyticsDimension
            ? $this->dimension->value
            : $this->dimension;

        return "/analytics/dimensions/{$dimension}";
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'start' => $this->start,
            'end' => $this->end,
            'query' => $this->queryString,
        ], fn ($value) => $value !== null);
    }

    /** @return array<int, AnalyticsDataPoint> */
    public function createDtoFromResponse(Response $response): array
    {
        return AnalyticsDataPoint::collect($response->json('data') ?? []);
    }
}
