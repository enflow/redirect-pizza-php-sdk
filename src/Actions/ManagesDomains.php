<?php

namespace RedirectPizza\PhpSdk\Actions;

use RedirectPizza\PhpSdk\Resources\Domain;

trait ManagesDomains
{
    public function domains(): array
    {
        return $this->transformCollection($this->get('domains')['data'], Domain::class);
    }

    public function domain(int $domainId): Domain
    {
        $attributes = $this->get("domains/{$domainId}")['data'];

        return new Domain($attributes, $this);
    }

    public function checkDomainDns(int $domainId): Domain
    {
        $attributes = $this->post('domains/' . $domainId . '/check-dns')['data'];

        return new Domain($attributes, $this);
    }
}
