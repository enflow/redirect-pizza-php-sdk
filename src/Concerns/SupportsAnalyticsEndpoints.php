<?php

namespace RedirectPizza\PhpSdk\Concerns;

use RedirectPizza\PhpSdk\Dto\AnalyticsDataPoint;
use RedirectPizza\PhpSdk\Dto\HitsTotal;
use RedirectPizza\PhpSdk\Dto\RawHit;
use RedirectPizza\PhpSdk\Enums\AnalyticsDimension;
use RedirectPizza\PhpSdk\RedirectPizza;
use RedirectPizza\PhpSdk\Requests\Analytics\GetDimensionsRequest;
use RedirectPizza\PhpSdk\Requests\Analytics\GetHitsTotalRequest;
use RedirectPizza\PhpSdk\Requests\Analytics\GetRawHitsRequest;
use RedirectPizza\PhpSdk\Requests\Analytics\GetTimeSeriesRequest;

/** @mixin RedirectPizza */
trait SupportsAnalyticsEndpoints
{
    public function hitsTotal(?string $start = null, ?string $end = null, ?string $query = null): HitsTotal
    {
        return $this->send(new GetHitsTotalRequest($start, $end, $query))->dto();
    }

    /** @return array<int, AnalyticsDataPoint> */
    public function timeSeries(?string $start = null, ?string $end = null, ?string $query = null): array
    {
        return $this->send(new GetTimeSeriesRequest($start, $end, $query))->dto();
    }

    /** @return iterable<int, AnalyticsDataPoint> */
    public function dimensions(
        AnalyticsDimension|string $dimension,
        ?string $start = null,
        ?string $end = null,
        ?string $query = null,
    ): iterable {
        $request = new GetDimensionsRequest($dimension, $start, $end, $query);

        /** @var iterable<int, AnalyticsDataPoint> $items */
        $items = $this->paginate($request)->items();

        return $items;
    }

    /** @return iterable<int, RawHit> */
    public function rawHits(?string $start = null, ?string $end = null, ?string $query = null): iterable
    {
        $request = new GetRawHitsRequest($start, $end, $query);

        /** @var iterable<int, RawHit> $items */
        $items = $this->paginate($request)->items();

        return $items;
    }
}
