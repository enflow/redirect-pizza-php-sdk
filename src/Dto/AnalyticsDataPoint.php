<?php

namespace RedirectPizza\PhpSdk\Dto;

class AnalyticsDataPoint
{
    public function __construct(
        public string $key,
        public int $count,
    ) {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            key: (string) $data['key'],
            count: (int) $data['count'],
        );
    }

    /** @param  array<int, array<string, mixed>>  $items
     *  @return array<int, self>
     */
    public static function collect(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
