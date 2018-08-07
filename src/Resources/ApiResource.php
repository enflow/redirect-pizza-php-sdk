<?php

namespace RedirectPizza\PhpSdk\Resources;

class ApiResource
{
    /** @var array */
    public $attributes = [];

    /** @var \RedirectPizza\PhpSdk\RedirectPizza */
    protected $redirectPizza;

    /**
     * @param  array $attributes
     * @param  \RedirectPizza\PhpSdk\RedirectPizza $redirectPizza
     */
    public function __construct(array $attributes, $redirectPizza = null)
    {
        $this->attributes = $attributes;

        $this->redirectPizza = $redirectPizza;

        $this->fill();
    }

    protected function fill()
    {
        foreach ($this->attributes as $key => $value) {
            $key = $this->camelCase($key);

            $this->{$key} = $value;
        }
    }

    protected function camelCase(string $key): string
    {
        $parts = explode('_', $key);

        foreach ($parts as $i => $part) {
            if ($i !== 0) {
                $parts[$i] = ucfirst($part);
            }
        }

        return str_replace(' ', '', implode(' ', $parts));
    }
}
