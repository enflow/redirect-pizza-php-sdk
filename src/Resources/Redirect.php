<?php

namespace RedirectPizza\PhpSdk\Resources;

use RedirectPizza\PhpSdk\RedirectPizza;

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

    /**
     * @param array              $attributes
     * @param RedirectPizza|null $redirectPizza
     */
    public function __construct(array $attributes, $redirectPizza = null)
    {
        parent::__construct($attributes, $redirectPizza);

        $this->sources = array_map(function (array $checkAttributes) {
            return new Source($checkAttributes);
        }, $this->sources ?? []);

        $this->domains = array_map(function (array $checkAttributes) {
            return new Domain($checkAttributes);
        }, $this->domains ?? []);
    }

    /**
     * Update the given redirect.
     *
     * @return void
     */
    public function update(array $data): void
    {
        $this->redirectPizza->updateRedirect($this->id, $data);
    }

    /**
     * Delete the given redirect.
     *
     * @return void
     */
    public function delete(): void
    {
        $this->redirectPizza->deleteRedirect($this->id);
    }
}
