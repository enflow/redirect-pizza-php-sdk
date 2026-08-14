<?php

namespace RedirectPizza\PhpSdk\Dto;

class Domain
{
    public function __construct(
        public int $id,
        public string $fqdn,
        public bool $isRootDomain,
        public bool $hsts,
        public bool $preventForeignEmbedding,
        public array $dns = [],
        public array $ssl = [],
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            fqdn: $data['fqdn'],
            isRootDomain: $data['is_root_domain'] ?? false,
            hsts: $data['hsts'] ?? false,
            preventForeignEmbedding: $data['prevent_foreign_embedding'] ?? false,
            dns: $data['dns'] ?? [],
            ssl: $data['ssl'] ?? [],
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null,
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
