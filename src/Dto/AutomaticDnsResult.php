<?php

namespace RedirectPizza\PhpSdk\Dto;

class AutomaticDnsResult
{
    public function __construct(
        public bool $successful,
        public string $output,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            successful: (bool) ($data['successful'] ?? false),
            output: (string) ($data['output'] ?? ''),
        );
    }
}
