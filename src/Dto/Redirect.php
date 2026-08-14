<?php

namespace RedirectPizza\PhpSdk\Dto;

class Redirect
{
    /**
     * @param  array<int, Source>  $sources
     * @param  array<int, Domain>  $domains
     * @param  array<int, string>  $tags
     */
    public function __construct(
        public int $id,
        public array $sources,
        public array $domains,
        public string $destination,
        public string $redirectType,
        public bool $keepQueryString,
        public bool $uriForwarding,
        public bool $tracking,
        public bool $paused = false,
        public array $tags = [],
        public ?string $notes = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            sources: Source::collect($data['sources'] ?? []),
            domains: Domain::collect($data['domains'] ?? []),
            destination: $data['destination'],
            redirectType: $data['redirect_type'],
            keepQueryString: $data['keep_query_string'] ?? false,
            uriForwarding: $data['uri_forwarding'] ?? false,
            tracking: $data['tracking'] ?? true,
            paused: $data['paused'] ?? false,
            tags: $data['tags'] ?? [],
            notes: $data['notes'] ?? null,
            createdAt: $data['created_at'] ?? null,
            updatedAt: $data['updated_at'] ?? null,
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
