<?php

namespace App\Domain\Recipe\ValueObject;

readonly class Difficulty {
    public function __construct(
        public float $difficulty
    ) {
    }
}
