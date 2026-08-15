<?php

namespace RedirectPizza\PhpSdk\Concerns;

use RedirectPizza\PhpSdk\Dto\EmailForward;
use RedirectPizza\PhpSdk\RedirectPizza;
use RedirectPizza\PhpSdk\Requests\EmailForwards\CreateEmailForwardRequest;
use RedirectPizza\PhpSdk\Requests\EmailForwards\DeleteEmailForwardRequest;
use RedirectPizza\PhpSdk\Requests\EmailForwards\GetEmailForwardRequest;
use RedirectPizza\PhpSdk\Requests\EmailForwards\GetEmailForwardsRequest;
use RedirectPizza\PhpSdk\Requests\EmailForwards\UpdateEmailForwardRequest;

/** @mixin RedirectPizza */
trait SupportsEmailForwardsEndpoints
{
    /** @return iterable<int, EmailForward> */
    public function emailForwards(): iterable
    {
        $request = new GetEmailForwardsRequest;

        /** @var iterable<int, EmailForward> $items */
        $items = $this->paginate($request)->items();

        return $items;
    }

    public function emailForward(int $emailForwardId): EmailForward
    {
        return $this->send(new GetEmailForwardRequest($emailForwardId))->dto();
    }

    public function createEmailForward(array $data): EmailForward
    {
        return $this->send(new CreateEmailForwardRequest($data))->dto();
    }

    public function updateEmailForward(int $emailForwardId, array $data): EmailForward
    {
        return $this->send(new UpdateEmailForwardRequest($emailForwardId, $data))->dto();
    }

    public function deleteEmailForward(int $emailForwardId): self
    {
        $this->send(new DeleteEmailForwardRequest($emailForwardId));

        return $this;
    }
}
