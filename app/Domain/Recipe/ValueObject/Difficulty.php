<?php

namespace App\Domain\Recipe\ValueObject;

use DateInterval;

class Difficulty {
    public function __construct(
        private readonly float $difficulty
    ) {
    }
}
