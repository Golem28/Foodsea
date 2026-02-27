<?php

namespace App\Infrastructure\Http;

class HttpResponse {
    private int $statusCode;
    private array $headers;
    private string $body;
    private ?array $json;

    public function __construct(int $statusCode, array $headers, string $body) {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;
        $this->json = json_decode($body, true);
    }

    public function getStatusCode(): int {
        return $this->statusCode;
    }

    public function getHeaders(): array {
        return $this->headers;
    }

    public function getBody(): string {
        return $this->body;
    }

    public function getJson(): ?array {
        return $this->json;
    }

    public function isSuccess(): bool {
        return $this->statusCode >= 200 && $this->statusCode < 300;
    }

    public function isClientError(): bool {
        return $this->statusCode >= 400 && $this->statusCode < 500;
    }

    public function isServerError(): bool {
        return $this->statusCode >= 500;
    }
}