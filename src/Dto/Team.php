<?php

namespace RedirectPizza\PhpSdk\Dto;

class Team
{
    /**
     * @param  array<int, string>  $nameservers
     */
    public function __construct(
        public int $id,
        public string $name,
        public ?string $summaryFrequency = null,
        public ?string $summaryReceivers = null,
        public array $hostnames = [],
        public array $hits = [],
        public array $users = [],
        public array $nameservers = [],
        public array $settings = [],
    ) {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            summaryFrequency: $data['summary_frequency'] ?? null,
            summaryReceivers: $data['summary_receivers'] ?? null,
            hostnames: $data['hostnames'] ?? [],
            hits: $data['hits'] ?? [],
            users: $data['users'] ?? [],
            nameservers: $data['nameservers'] ?? [],
            settings: $data['settings'] ?? [],
        );
    }
}
