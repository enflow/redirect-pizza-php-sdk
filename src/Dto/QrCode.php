<?php

namespace RedirectPizza\PhpSdk\Dto;

class QrCode
{
    public function __construct(
        public string $url,
        public string $image,
        public ?string $destination = null,
        public ?string $filename = null,
    ) {
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            url: $data['url'],
            image: $data['image'],
            destination: $data['destination'] ?? null,
            filename: $data['filename'] ?? null,
        );
    }
}
