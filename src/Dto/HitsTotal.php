<?php

namespace RedirectPizza\PhpSdk\Dto;

class HitsTotal
{
    public function __construct(
        public int $count,
        public array $filters = [],
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            count: $data['data']['count'] ?? 0,
            filters: $data['filters'] ?? [],
        );
    }
}
