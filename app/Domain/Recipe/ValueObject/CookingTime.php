<?php

namespace App\Domain\Recipe\ValueObject;

use DateInterval;

class CookingTime {
    public function __construct(
        private readonly DateInterval $cookingTime,
        private readonly DateInterval $restingTime,
    ) {
    }

    public function getTotalTime(): DateInterval {
        return $this->cookingTime + $this->restingTime;
    }
}
