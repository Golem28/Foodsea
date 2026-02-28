<?php

namespace App\Domain\Recipe\ValueObject;

readonly class Url {
    public function __construct(
        private string $url
    ) {
    }
}
