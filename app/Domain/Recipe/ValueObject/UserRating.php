<?php

namespace App\Domain\Recipe\ValueObject;

class UserRating {
    public function __construct(
        float $averageRating,
        int $votesCount,
    ) {
    }
}
