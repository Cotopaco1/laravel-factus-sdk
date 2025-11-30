<?php

namespace Cotopaco\Factus\Exceptions;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

class FactusValidationException extends RequestException
{
    public array $errors = [];

    public function __construct(Response $response)
    {
        parent::__construct($response);

        $data = $response->json('data');

        $this->errors = $data['errors'] ?? [];
    }

    public function hasError(string $field): bool
    {
        return array_key_exists($field, $this->errors);
    }

    public function getFieldErrors(string $field): array
    {
        return $this->errors[$field] ?? [];
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
