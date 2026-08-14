<?php

namespace RedirectPizza\PhpSdk\Dto;

class Team
{
    public function __construct(
        public int $id,
        public string $name,
        public array $hostnames = [],
        public array $hits = [],
        public array $users = [],
    ) {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            hostnames: $data['hostnames'] ?? [],
            hits: $data['hits'] ?? [],
            users: $data['users'] ?? [],
        );
    }
}
