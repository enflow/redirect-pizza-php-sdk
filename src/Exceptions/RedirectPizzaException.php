<?php

namespace RedirectPizza\PhpSdk\Exceptions;

use Exception;
use Saloon\Http\Response;

class RedirectPizzaException extends Exception
{
    public function __construct(
        public Response $response,
        string $message,
        int $code,
    ) {
        parent::__construct($message, $code);
    }
}
