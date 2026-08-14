<?php

namespace RedirectPizza\PhpSdk;

use RedirectPizza\PhpSdk\Concerns\SupportsAnalyticsEndpoints;
use RedirectPizza\PhpSdk\Concerns\SupportsDomainsEndpoints;
use RedirectPizza\PhpSdk\Concerns\SupportsEmailForwardsEndpoints;
use RedirectPizza\PhpSdk\Concerns\SupportsRedirectsEndpoints;
use RedirectPizza\PhpSdk\Concerns\SupportsTeamEndpoints;
use RedirectPizza\PhpSdk\Concerns\SupportsUsersEndpoints;
use RedirectPizza\PhpSdk\Concerns\SupportsUtilsEndpoints;
use RedirectPizza\PhpSdk\Exceptions\RedirectPizzaException;
use RedirectPizza\PhpSdk\Exceptions\ValidationException;
use RedirectPizza\PhpSdk\Requests\Analytics\GetRawHitsRequest;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Connector;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\PaginationPlugin\Contracts\HasPagination;
use Saloon\PaginationPlugin\CursorPaginator;
use Saloon\PaginationPlugin\PagedPaginator;
use Saloon\PaginationPlugin\Paginator;
use Saloon\Traits\Plugins\AcceptsJson;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;
use Throwable;

class RedirectPizza extends Connector implements HasPagination
{
    use AcceptsJson;
    use AlwaysThrowOnErrors;
    use SupportsAnalyticsEndpoints;
    use SupportsDomainsEndpoints;
    use SupportsEmailForwardsEndpoints;
    use SupportsRedirectsEndpoints;
    use SupportsTeamEndpoints;
    use SupportsUsersEndpoints;
    use SupportsUtilsEndpoints;

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
        if ($request instanceof GetRawHitsRequest) {
            return new class(connector: $this, request: $request) extends CursorPaginator
            {
                protected function isLastPage(Response $response): bool
                {
                    return $response->json('meta.next_cursor') === null;
                }

                protected function getNextCursor(Response $response): int|string
                {
                    return $response->json('meta.next_cursor');
                }

                protected function getPageItems(Response $response, Request $request): array
                {
                    return $request->createDtoFromResponse($response);
                }
            };
        }

        return new class(connector: $this, request: $request) extends PagedPaginator
        {
            protected function isLastPage(Response $response): bool
            {
                $currentPage = $response->json('meta.current_page');
                $lastPage = $response->json('meta.last_page');

                if ($lastPage !== null) {
                    return $currentPage === $lastPage;
                }

                $items = $response->json('data') ?? [];
                $perPage = $response->json('meta.per_page');

                if ($perPage === null) {
                    return true;
                }

                return count($items) < (int) $perPage;
            }

            protected function getPageItems(Response $response, Request $request): array
            {
                return $request->createDtoFromResponse($response);
            }
        };
    }
}
