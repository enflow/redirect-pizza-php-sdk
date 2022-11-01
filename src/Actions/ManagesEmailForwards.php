<?php

namespace RedirectPizza\PhpSdk\Actions;

use RedirectPizza\PhpSdk\Resources\EmailForward;

trait ManagesEmailForwards
{
    public function emailForwards(): array
    {
        return $this->transformCollection($this->get('emailForwards')['data'], EmailForward::class);
    }

    public function EmailForward(int $EmailForwardId): EmailForward
    {
        $attributes = $this->get("email-forwards/{$EmailForwardId}")['data'];

        return new EmailForward($attributes, $this);
    }

    public function createEmailForward(array $data): EmailForward
    {
        $attributes = $this->post('email-forwards', $data)['data'];

        return new EmailForward($attributes, $this);
    }

    public function updateEmailForward(int $EmailForwardId, array $data): EmailForward
    {
        $attributes = $this->put("email-forwards/{$EmailForwardId}", $data)['data'];

        return new EmailForward($attributes, $this);
    }

    public function deleteEmailForward(int $EmailForwardId): void
    {
        $this->delete("emailForwards/$EmailForwardId");
    }
}
