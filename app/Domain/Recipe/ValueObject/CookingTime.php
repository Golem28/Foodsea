<?php

namespace App\Domain\Recipe\ValueObject;

use DateInterval;

readonly class CookingTime {
    public function __construct(
        private DateInterval $cookingTime,
        private DateInterval $restingTime,
    ) {
    }

    public function getPreparationTime(): DateInterval {
        return $this->cookingTime;
    }

    public function getRestingTime(): DateInterval {
        return $this->restingTime;
    }

    public function getTotalTime(): DateInterval {
        return $this->cookingTime + $this->restingTime;
    }
}
