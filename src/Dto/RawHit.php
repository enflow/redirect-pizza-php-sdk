<?php

namespace RedirectPizza\PhpSdk\Dto;

class RawHit
{
    public function __construct(
        public int|string $id,
        public ?string $createdAt = null,
        public ?int $redirectId = null,
        public ?string $tracking = null,
        public ?string $redirectType = null,
        public ?string $fullUrl = null,
        public ?string $destinationUrl = null,
        public ?string $ip = null,
        public ?string $scheme = null,
        public ?string $method = null,
        public int|bool|null $isCrawler = null,
        public int|bool|null $wafBlocked = null,
        public mixed $wafRules = null,
        public ?string $continentName = null,
        public ?string $continent = null,
        public ?string $country = null,
        public ?string $subdivisionName = null,
        public ?string $subdivisionCode = null,
        public ?string $city = null,
        public ?string $userAgent = null,
        public ?string $referer = null,
        public ?string $refererHost = null,
        public ?string $browserName = null,
        public ?string $operatingSystem = null,
        public ?string $platform = null,
        public ?string $deviceType = null,
    ) {}

    public static function fromResponse(array $data): self
    {
        return new self(
            id: $data['id'],
            createdAt: $data['created_at'] ?? null,
            redirectId: $data['redirect_id'] ?? null,
            tracking: $data['tracking'] ?? null,
            redirectType: $data['redirect_type'] ?? null,
            fullUrl: $data['full_url'] ?? null,
            destinationUrl: $data['destination_url'] ?? null,
            ip: $data['ip'] ?? null,
            scheme: $data['scheme'] ?? null,
            method: $data['method'] ?? null,
            isCrawler: $data['is_crawler'] ?? null,
            wafBlocked: $data['waf_blocked'] ?? null,
            wafRules: $data['waf_rules'] ?? null,
            continentName: $data['continent_name'] ?? null,
            continent: $data['continent'] ?? null,
            country: $data['country'] ?? null,
            subdivisionName: $data['subdivision_name'] ?? null,
            subdivisionCode: $data['subdivision_code'] ?? null,
            city: $data['city'] ?? null,
            userAgent: $data['user_agent'] ?? null,
            referer: $data['referer'] ?? null,
            refererHost: $data['referer_host'] ?? null,
            browserName: $data['browser_name'] ?? null,
            operatingSystem: $data['operating_system'] ?? null,
            platform: $data['platform'] ?? null,
            deviceType: $data['device_type'] ?? null,
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
