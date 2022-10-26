<?php

namespace RedirectPizza\PhpSdk\Actions;

use RedirectPizza\PhpSdk\Resources\Team;

trait ManagesTeam
{
    public function team(): Team
    {
        $attributes = $this->get("team")['data'];

        return new Team($attributes, $this);
    }
}
