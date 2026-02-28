<?php

namespace App\Domain\Recipe\ValueObject;

readonly class ViewCount {
    public function __construct(
        private readonly int $viewCount,
    ) {

    }
}
