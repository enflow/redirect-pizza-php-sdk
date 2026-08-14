<?php

namespace RedirectPizza\PhpSdk\Concerns;

use RedirectPizza\PhpSdk\Dto\User;
use RedirectPizza\PhpSdk\RedirectPizza;
use RedirectPizza\PhpSdk\Requests\Users\DeleteUserRequest;
use RedirectPizza\PhpSdk\Requests\Users\GetUsersRequest;
use RedirectPizza\PhpSdk\Requests\Users\InviteUserRequest;
use RedirectPizza\PhpSdk\Requests\Users\UpdateUserRequest;

/** @mixin RedirectPizza */
trait SupportsUsersEndpoints
{
    /** @return iterable<int, User> */
    public function users(): iterable
    {
        $request = new GetUsersRequest;

        /** @var iterable<int, User> $items */
        $items = $this->paginate($request)->items();

        return $items;
    }

    public function inviteUser(array $data): User
    {
        return $this->send(new InviteUserRequest($data))->dto();
    }

    public function updateUser(int $userId, array $data): User
    {
        return $this->send(new UpdateUserRequest($userId, $data))->dto();
    }

    public function deleteUser(int $userId): self
    {
        $this->send(new DeleteUserRequest($userId));

        return $this;
    }
}
