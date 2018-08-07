<?php

namespace RedirectPizza\PhpSdk\Exceptions;

use Exception;
use GuzzleHttp\Psr7\Response;

class ApiException extends Exception
{
    public function __construct(Response $response)
    {
        parent::__construct('The API call failed with status code ' . $response->getStatusCode() . ': ' . (string) $response->getBody(), $response->getStatusCode());
    }
}
