<?php

namespace RedirectPizza\PhpSdk\Actions;

use RedirectPizza\PhpSdk\Resources\EmailForward;

trait ManagesEmailForwards
{
    public function emailForwards(): array
    {
        return $this->transformCollection($this->get('email-forwards')['data'], EmailForward::class);
    }

    public function EmailForward(int $emailForwardId): EmailForward
    {
        $attributes = $this->get("email-forwards/{$emailForwardId}")['data'];

        return new EmailForward($attributes, $this);
    }

    public function createEmailForward(array $data): EmailForward
    {
        $attributes = $this->post('email-forwards', $data)['data'];

        return new EmailForward($attributes, $this);
    }

    public function updateEmailForward(int $emailForwardId, array $data): EmailForward
    {
        $attributes = $this->put("email-forwards/{$emailForwardId}", $data)['data'];

        return new EmailForward($attributes, $this);
    }

    public function deleteEmailForward(int $emailForwardId): void
    {
        $this->delete("emailForwards/$emailForwardId");
    }
}
