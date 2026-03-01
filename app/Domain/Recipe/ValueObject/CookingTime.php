<?php

namespace App\Domain\Recipe\ValueObject;

use DateInterval;
use Nette\Schema\ValidationException;

readonly class CookingTime {
    public function __construct(
        private int $preparationTime,
        private int $restingTime,
    ) {
        if ($preparationTime < 5) {
            throw new ValidationException("Cooking time should be at least 5 minutes to be valid.");
        }
    }

    public function getPreparationTime(): int {
        return $this->preparationTime;
    }

    public function getRestingTime(): int {
        return $this->restingTime;
    }

    public function getTotalTime(): int {
        return $this->preparationTime + $this->restingTime;
    }
}
