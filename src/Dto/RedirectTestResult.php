<?php

namespace RedirectPizza\PhpSdk\Dto;

class RedirectTestResult
{
    public function __construct(
        public string $url,
        public string $status,
        public ?int $statusCode = null,
        public ?array $redirect = null,
        public array $headers = [],
        public ?float $duration = null,
        public ?array $probe = null,
        public ?string $explanation = null,
        public ?string $error = null,
        public ?string $nextTestUrl = null,
    ) {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            url: $data['url'],
            status: $data['status'],
            statusCode: isset($data['status_code']) ? (int) $data['status_code'] : null,
            redirect: is_array($data['redirect'] ?? null) ? $data['redirect'] : null,
            headers: $data['headers'] ?? [],
            duration: isset($data['duration']) ? (float) $data['duration'] : null,
            probe: is_array($data['probe'] ?? null) ? $data['probe'] : null,
            explanation: $data['explanation'] ?? null,
            error: $data['error'] ?? null,
            nextTestUrl: $data['next_test_url'] ?? null,
        );
    }
}
