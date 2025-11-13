<?php

namespace Pantry\Exceptions;

use Throwable;

class RequestException extends PantryException
{
    private int $httpCode;

    public function __construct(string $message, int $httpCode, int $code = 0, ?Throwable $previous = null)
    {
        $this->httpCode = $httpCode;
        parent::__construct($message, $code, $previous);
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
