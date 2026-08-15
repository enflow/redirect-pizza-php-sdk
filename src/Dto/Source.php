<?php

namespace RedirectPizza\PhpSdk\Dto;

class Source
{
    public function __construct(
        public int $id,
        public string $url,
        public bool $regex = false,
        public bool $paused = false,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            url: $data['url'],
            regex: $data['regex'] ?? false,
            paused: $data['paused'] ?? false,
        );
    }

    /** @param  array<int, array<string, mixed>>  $items
     * @return array<int, self>
     */
    public static function collect(array $items): array
    {
        return array_map(fn (array $item) => self::fromResponse($item), $items);
    }
}
