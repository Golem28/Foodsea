<?php

namespace App\Domain\Recipe;

class UserRating {
    public function __construct(
        float $averageRating,
        int $votesCount,
    ) {
    }
}
