<?php

namespace RedirectPizza\PhpSdk\Resources;

class Domain extends ApiResource
{
    public int $id;

    public string $fqdn;

    public bool $isRootDomain;

    public bool $hsts;

    public bool $preventForeignEmbedding;

    public array $dns;

    public array $ssl;

    public function checkDns(): Domain
    {
        return $this->redirectPizza->checkDomainDns($this->id);
    }
}
