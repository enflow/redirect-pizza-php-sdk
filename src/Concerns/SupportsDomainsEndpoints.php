<?php

namespace RedirectPizza\PhpSdk\Concerns;

use RedirectPizza\PhpSdk\Dto\AutomaticDnsResult;
use RedirectPizza\PhpSdk\Dto\Domain;
use RedirectPizza\PhpSdk\RedirectPizza;
use RedirectPizza\PhpSdk\Requests\Domains\ApplyAutomaticDnsRequest;
use RedirectPizza\PhpSdk\Requests\Domains\CheckDomainDnsRequest;
use RedirectPizza\PhpSdk\Requests\Domains\DeleteDomainRequest;
use RedirectPizza\PhpSdk\Requests\Domains\GetDomainRequest;
use RedirectPizza\PhpSdk\Requests\Domains\GetDomainsRequest;
use RedirectPizza\PhpSdk\Requests\Domains\UpdateDomainRequest;

/** @mixin RedirectPizza */
trait SupportsDomainsEndpoints
{
    /** @return iterable<int, Domain> */
    public function domains(?string $query = null): iterable
    {
        $request = new GetDomainsRequest($query);

        /** @var iterable<int, Domain> $items */
        $items = $this->paginate($request)->items();

        return $items;
    }

    public function domain(int $domainId): Domain
    {
        return $this->send(new GetDomainRequest($domainId))->dto();
    }

    public function updateDomain(int $domainId, array $data): Domain
    {
        return $this->send(new UpdateDomainRequest($domainId, $data))->dto();
    }

    public function deleteDomain(int $domainId): self
    {
        $this->send(new DeleteDomainRequest($domainId));

        return $this;
    }

    public function checkDomainDns(int $domainId): Domain
    {
        return $this->send(new CheckDomainDnsRequest($domainId))->dto();
    }

    public function applyAutomaticDns(int $domainId): AutomaticDnsResult
    {
        return $this->send(new ApplyAutomaticDnsRequest($domainId))->dto();
    }
}
