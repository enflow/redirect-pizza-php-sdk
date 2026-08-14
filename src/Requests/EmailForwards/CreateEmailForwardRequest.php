<?php

namespace RedirectPizza\PhpSdk\Requests\EmailForwards;

use RedirectPizza\PhpSdk\Dto\EmailForward;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateEmailForwardRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected array $data,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/email-forwards';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }

    public function createDtoFromResponse(Response $response): EmailForward
    {
        return EmailForward::fromResponse($response->json('data'));
    }
}
