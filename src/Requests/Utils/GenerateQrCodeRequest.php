<?php

namespace RedirectPizza\PhpSdk\Requests\Utils;

use RedirectPizza\PhpSdk\Dto\QrCode;
use Saloon\Enums\Method;
use Saloon\Http\Auth\NullAuthenticator;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GenerateQrCodeRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $url,
        protected string $format = 'json',
    ) {}

    public function resolveEndpoint(): string
    {
        return '/qr';
    }

    protected function defaultAuth(): NullAuthenticator
    {
        return new NullAuthenticator;
    }

    protected function defaultQuery(): array
    {
        return [
            'url' => $this->url,
            'format' => $this->format,
        ];
    }

    public function createDtoFromResponse(Response $response): QrCode
    {
        return QrCode::fromResponse($response->json());
    }
}
