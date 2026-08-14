<?php

namespace RedirectPizza\PhpSdk\Concerns;

use RedirectPizza\PhpSdk\Dto\Domain;
use RedirectPizza\PhpSdk\RedirectPizza;
use RedirectPizza\PhpSdk\Requests\Domains\CheckDomainDnsRequest;
use RedirectPizza\PhpSdk\Requests\Domains\GetDomainRequest;
use RedirectPizza\PhpSdk\Requests\Domains\GetDomainsRequest;

/** @mixin RedirectPizza */
trait SupportsDomainsEndpoints
{
    /** @return iterable<int, Domain> */
    public function domains(): iterable
    {
        $request = new GetDomainsRequest;

        /** @var iterable<int, Domain> $items */
        $items = $this->paginate($request)->items();

        return $items;
    }

    public function domain(int $domainId): Domain
    {
        return $this->send(new GetDomainRequest($domainId))->dto();
    }

    public function checkDomainDns(int $domainId): Domain
    {
        return $this->send(new CheckDomainDnsRequest($domainId))->dto();
    }
}
