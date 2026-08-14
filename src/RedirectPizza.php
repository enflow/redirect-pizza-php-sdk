<?php

namespace RedirectPizza\PhpSdk;

use RedirectPizza\PhpSdk\Concerns\SupportsDomainsEndpoints;
use RedirectPizza\PhpSdk\Concerns\SupportsEmailForwardsEndpoints;
use RedirectPizza\PhpSdk\Concerns\SupportsRedirectsEndpoints;
use RedirectPizza\PhpSdk\Concerns\SupportsTeamEndpoints;
use RedirectPizza\PhpSdk\Exceptions\RedirectPizzaException;
use RedirectPizza\PhpSdk\Exceptions\ValidationException;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\PagedPaginator;
use Saloon\PaginationPlugin\Paginator;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Throwable;

class RedirectPizza extends Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use SupportsDomainsEndpoints;
    use SupportsEmailForwardsEndpoints;
    use SupportsRedirectsEndpoints;
    use SupportsTeamEndpoints;

    protected string $apiToken;

    protected string $baseUrl;

    protected int $timeoutInSeconds;

    public function __construct(
        string $apiToken,
        string $baseUrl = 'https://redirect.pizza/api/v1',
        int $timeoutInSeconds = 10,
    ) {
        $this->apiToken = $apiToken;
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeoutInSeconds = $timeoutInSeconds;
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultAuth(): TokenAuthenticator
    {
        return new TokenAuthenticator($this->apiToken);
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    protected function defaultConfig(): array
    {
        return [
            'timeout' => $this->timeoutInSeconds,
        ];
    }

    public function getRequestException(Response $response, ?Throwable $senderException): ?Throwable
    {
        if ($response->status() === 422) {
            return new ValidationException($response);
        }

        return new RedirectPizzaException(
            $response,
            $senderException?->getMessage() ?? 'Request failed',
            $senderException?->getCode() ?? 0,
        );
    }

    public function paginate(Request $request): Paginator
    {
        return new class(connector: $this, request: $request) extends PagedPaginator {
            protected function isLastPage(Response $response): bool
            {
                $currentPage = $response->json('meta.current_page');
                $lastPage = $response->json('meta.last_page');

                return $currentPage === $lastPage;
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $request->createDtoFromResponse($response);
            }
        };
    }
}
