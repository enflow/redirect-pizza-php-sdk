<?php

namespace RedirectPizza\PhpSdk\Concerns;

use RedirectPizza\PhpSdk\Dto\Team;
use RedirectPizza\PhpSdk\RedirectPizza;
use RedirectPizza\PhpSdk\Requests\Team\GetTeamRequest;

/** @mixin RedirectPizza */
trait SupportsTeamEndpoints
{
    public function team(): Team
    {
        return $this->send(new GetTeamRequest)->dto();
    }
}
