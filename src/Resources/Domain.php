<?php

namespace RedirectPizza\PhpSdk\Resources;

class Domain extends ApiResource
{
    public int $id;

    public string $fqdn;

    public bool $isRootDomain;

    public array $dns;

    public array $ssl;
}
