<?php

namespace App\Domain\Recipe\ValueObject;

readonly class UserRating {
    public function __construct(
        float $averageRating,
        int $votesCount,
    ) {
    }
}
