<?php

namespace App\Domain\Recipe\ValueObject;

class ViewCount {
    public function __construct(
        private readonly int $viewCount,
    ) {

    }
}
