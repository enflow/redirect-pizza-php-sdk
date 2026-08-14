<?php

namespace RedirectPizza\PhpSdk\Dto;

class EmailForward
{
    public function __construct(
        public int $id,
        public string $alias,
        public string $destination,
        public ?Domain $domain = null,
    ) {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            alias: $data['alias'],
            destination: $data['destination'],
            domain: isset($data['domain']) && is_array($data['domain'])
                ? Domain::fromResponse($data['domain'])
                : null,
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
