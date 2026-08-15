<?php

namespace RedirectPizza\PhpSdk\Requests\EmailForwards;

use RedirectPizza\PhpSdk\Dto\EmailForward;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetEmailForwardRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected int $emailForwardId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/email-forwards/{$this->emailForwardId}";
    }

    public function createDtoFromResponse(Response $response): EmailForward
    {
        return EmailForward::fromResponse($response->json('data'));
    }
}
