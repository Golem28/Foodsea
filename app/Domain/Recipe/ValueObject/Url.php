<?php

namespace App\Domain\Recipe\ValueObject;

class Url {
    public function __construct(
        private string $url
    ) {
    }
}
