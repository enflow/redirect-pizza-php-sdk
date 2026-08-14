<?php

namespace RedirectPizza\PhpSdk\Concerns;

use RedirectPizza\PhpSdk\Dto\Team;
use RedirectPizza\PhpSdk\RedirectPizza;
use RedirectPizza\PhpSdk\Requests\Team\GetTeamRequest;
use RedirectPizza\PhpSdk\Requests\Team\UpdateTeamRequest;

/** @mixin RedirectPizza */
trait SupportsTeamEndpoints
{
    public function team(): Team
    {
        return $this->send(new GetTeamRequest)->dto();
    }

    public function updateTeam(array $data): Team
    {
        return $this->send(new UpdateTeamRequest($data))->dto();
    }
}
