<?php

namespace App\Infrastructure\Http;

use Exception;

class RestError extends Exception {
    private int $statusCode;
    private string $errorMessage;

    public function __construct(int $statusCode, string $errorMessage) {
        parent::__construct($errorMessage);
        $this->statusCode = $statusCode;
        $this->errorMessage = $errorMessage;
    }

    public function getStatusCode(): int {
        return $this->statusCode;
    }

    public function getErrorMessage(): string {
        return $this->errorMessage;
    }
}
