<?php

namespace RedirectPizza\PhpSdk\Exceptions;

use Saloon\Http\Response;

class ValidationException extends RedirectPizzaException
{
    /** @var array<string, array<int, string>> */
    protected array $errors = [];

    public function __construct(Response $response)
    {
        $data = $response->json();

        $this->errors = $data['errors'] ?? [];

        parent::__construct($response, $this->buildMessage($data), $response->status());
    }

    protected function buildMessage(array $data): string
    {
        $baseMessage = $data['message'] ?? 'Validation failed';

        if ($this->errors === []) {
            return $baseMessage;
        }

        $fieldMessages = [];
        foreach ($this->errors as $field => $messages) {
            $fieldMessages[] = "{$field}: ".implode(', ', $messages);
        }

        return $baseMessage.' ('.implode('; ', $fieldMessages).')';
    }

    /** @return array<string, array<int, string>> */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /** @return array<int, string> */
    public function getErrorsForField(string $field): array
    {
        return $this->errors[$field] ?? [];
    }

    public function hasErrorsForField(string $field): bool
    {
        return isset($this->errors[$field]);
    }

    /** @return array<int, string> */
    public function getAllErrorMessages(): array
    {
        if ($this->errors === []) {
            return [];
        }

        return array_merge(...array_values($this->errors));
    }
}
