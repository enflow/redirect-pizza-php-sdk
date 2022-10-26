<?php

namespace RedirectPizza\PhpSdk\Exceptions;

use Exception;

class ValidationException extends Exception
{
    public function __construct(public array $errors)
    {
        parent::__construct($this->errors['message'] ?? 'The given data failed to pass validation.');
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
