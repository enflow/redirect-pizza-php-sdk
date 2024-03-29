<?php

namespace RedirectPizza\PhpSdk\Resources;

class Redirect extends ApiResource
{
    public int $id;

    public array $sources;

    public array $domains;

    public string $destination;

    public string $redirectType;

    public bool $keepQueryString;

    public bool $uriForwarding;

    public bool $tracking;

    public array $tags;

    public ?string $notes;

    public function __construct(array $attributes, $redirectPizza = null)
    {
        parent::__construct($attributes, $redirectPizza);

        $this->sources = array_map(fn (array $sourceAttributes) => new Source($sourceAttributes, $redirectPizza), $this->sources ?? []);

        $this->domains = array_map(fn (array $domainAttributes) => new Domain($domainAttributes, $redirectPizza), $this->domains ?? []);
    }

    public function update(array $data): void
    {
        $this->redirectPizza->updateRedirect($this->id, $data);
    }

    public function delete(): void
    {
        $this->redirectPizza->deleteRedirect($this->id);
    }
}
