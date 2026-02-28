<?php

namespace App\Domain\Recipe\ValueObject;

class Difficulty {
    public function __construct(
        private readonly float $difficulty
    ) {
    }
}
