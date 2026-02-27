<?php

namespace App\Domain\Common\Abstractions;

use BadMethodCallException;

abstract class ResponseBase implements Response {
    private bool $success;

    public function __construct(
        private $data,
        private string $error,
    ) {
        $this->success = empty($error);
    }

    public function isSuccess(): bool {
        return $this->success;
    }

    public function getData() {
        if (!$this->success) {
            throw new BadMethodCallException("Cannot get data from a failed response: " . $this->error);
        }
        return $this->data;
    }

    public function getError(): string {
        if ($this->success) {
            throw new BadMethodCallException("Cannot get error from a successful response");
        }
        return $this->error;
    }
}
