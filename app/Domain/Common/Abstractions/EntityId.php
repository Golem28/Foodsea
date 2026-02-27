<?php

namespace App\Domain\Common\Abstractions;

class EntityId {
    public function __construct(
        private string $value
    ) {
    }

    public static function generateUniqueId(): self {
        return new self(uniqid('', true));
    }

    public function __toString(): string {
        return $this->value;
    }
}
