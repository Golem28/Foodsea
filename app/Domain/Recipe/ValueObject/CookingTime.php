<?php

namespace App\Domain\Recipe\ValueObject;

use DateInterval;

class CookingTime {
    public function __construct(
        private readonly DateInterval $cookingTime,
        private readonly DateInterval $restingTime,
    ) {
    }

    public function getCookingTime(): DateInterval {
        return $this->cookingTime;
    }

    public function getRestingTime(): DateInterval {
        return $this->restingTime;
    }

    public function getTotalTime(): DateInterval {
        return $this->cookingTime + $this->restingTime;
    }
}
