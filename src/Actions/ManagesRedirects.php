<?php

namespace RedirectPizza\PhpSdk\Actions;

use RedirectPizza\PhpSdk\Resources\Redirect;

trait ManagesRedirects
{
    public function redirects(): array
    {
        return $this->transformCollection($this->get('redirects')['data'], Redirect::class);
    }

    public function redirect(int $redirectId): Redirect
    {
        $attributes = $this->get("redirects/{$redirectId}")['data'];

        return new Redirect($attributes, $this);
    }

    public function createRedirect(array $data): Redirect
    {
        $attributes = $this->post('redirects', $data)['data'];

        return new Redirect($attributes, $this);
    }

    public function updateRedirect(int $redirectId, array $data): Redirect
    {
        $attributes = $this->put("redirects/{$redirectId}", $data)['data'];

        return new Redirect($attributes, $this);
    }

    public function deleteRedirect(int $redirectId)
    {
        $this->delete("redirects/$redirectId");
    }
}
