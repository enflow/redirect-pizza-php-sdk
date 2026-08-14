<?php

namespace RedirectPizza\PhpSdk\Concerns;

use RedirectPizza\PhpSdk\Dto\Redirect;
use RedirectPizza\PhpSdk\RedirectPizza;
use RedirectPizza\PhpSdk\Requests\Redirects\CreateRedirectRequest;
use RedirectPizza\PhpSdk\Requests\Redirects\DeleteRedirectRequest;
use RedirectPizza\PhpSdk\Requests\Redirects\GetRedirectRequest;
use RedirectPizza\PhpSdk\Requests\Redirects\GetRedirectsRequest;
use RedirectPizza\PhpSdk\Requests\Redirects\UpdateRedirectRequest;

/** @mixin RedirectPizza */
trait SupportsRedirectsEndpoints
{
    /** @return iterable<int, Redirect> */
    public function redirects(): iterable
    {
        $request = new GetRedirectsRequest;

        /** @var iterable<int, Redirect> $items */
        $items = $this->paginate($request)->items();

        return $items;
    }

    public function redirect(int $redirectId): Redirect
    {
        return $this->send(new GetRedirectRequest($redirectId))->dto();
    }

    public function createRedirect(array $data): Redirect
    {
        return $this->send(new CreateRedirectRequest($data))->dto();
    }

    public function updateRedirect(int $redirectId, array $data): Redirect
    {
        return $this->send(new UpdateRedirectRequest($redirectId, $data))->dto();
    }

    public function deleteRedirect(int $redirectId): self
    {
        $this->send(new DeleteRedirectRequest($redirectId));

        return $this;
    }
}
