<?php

namespace RedirectPizza\PhpSdk\Resources;

class Team extends ApiResource
{
    public int $id;

    public string $name;

    public array $hostnames;

    public array $hits;

    public array $users;
}
