<?php

namespace RedirectPizza\PhpSdk\Resources;

use RedirectPizza\PhpSdk\RedirectPizza;

class Redirect extends ApiResource
{
    public $id;

    public $sources;

    public $domains;

    public $destination;

    public $redirectType;

    public $keepQueryString;

    public $uriForwarding;
    
    public $tracking;

    /**
     * @param array              $attributes
     * @param RedirectPizza|null $redirectPizza
     */
    public function __construct(array $attributes, $redirectPizza = null)
    {
        parent::__construct($attributes, $redirectPizza);

        $this->sources = array_map(function (array $checkAttributes) {
            return new Source($checkAttributes);
        }, $this->sources);
    }

    /**
     * Update the given redirect.
     *
     * @return void
     */
    public function update(array $data)
    {
        $this->redirectPizza->updateRedirect($this->id, $data);
    }

    /**
     * Delete the given redirect.
     *
     * @return void
     */
    public function delete()
    {
        $this->redirectPizza->deleteRedirect($this->id);
    }
}
