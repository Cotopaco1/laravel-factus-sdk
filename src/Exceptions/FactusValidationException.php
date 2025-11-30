<?php

namespace Cotopaco\Factus\Exceptions;

use Illuminate\Http\Client\Response;
use Illuminate\Validation\ValidationException;

class FactusValidationException extends ValidationException
{
    public static function fromResponse(Response $response): self
    {
        $data = $response->json('data') ?? [];
        $errors = $data['errors'] ?? [];

        return static::withMessages($errors);
    }
}
