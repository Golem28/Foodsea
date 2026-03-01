<?php

namespace App\Domain\Recipe\ValueObject;

readonly class RecipeTag {
    public function __construct(
        public string $name
    ) {
    }
}
