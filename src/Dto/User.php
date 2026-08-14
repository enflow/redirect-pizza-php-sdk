<?php

namespace RedirectPizza\PhpSdk\Dto;

class User
{
    /**
     * @param  array<int, string>  $tags
     */
    public function __construct(
        public int $id,
        public string $email,
        public string $role,
        public string $status,
        public string $accessType,
        public array $tags = [],
        public ?string $createdAt = null,
    ) {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            email: $data['email'],
            role: $data['role'],
            status: $data['status'],
            accessType: $data['access_type'],
            tags: $data['tags'] ?? [],
            createdAt: $data['created_at'] ?? null,
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
