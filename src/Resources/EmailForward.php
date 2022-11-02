<?php

namespace RedirectPizza\PhpSdk\Resources;

class EmailForward extends ApiResource
{
    public int $id;

    public array $domain;

    public string $alias;

    public string $destination;

    public function update(array $data): void
    {
        $this->redirectPizza->updateEmailForward($this->id, $data);
    }

    public function delete(): void
    {
        $this->redirectPizza->deleteEmailForward($this->id);
    }
}
