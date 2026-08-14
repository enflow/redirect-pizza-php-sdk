<?php

namespace RedirectPizza\PhpSdk\Requests\Analytics;

use RedirectPizza\PhpSdk\Dto\AnalyticsDataPoint;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetTimeSeriesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?string $start = null,
        protected ?string $end = null,
        protected ?string $filter = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/analytics/time-series';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'start' => $this->start,
            'end' => $this->end,
            'query' => $this->filter,
        ], fn ($value) => $value !== null);
    }

    /** @return array<int, AnalyticsDataPoint> */
    public function createDtoFromResponse(Response $response): array
    {
        return AnalyticsDataPoint::collect($response->json('data') ?? []);
    }
}
