<?php

namespace App\Domain\Recipe\Entity;

use App\Domain\Recipe\ValueObject\RecipeTagId;
use stdClass;

readonly class RecipeTag {
    public function __construct(
        public string $name
    ) {
    }
}
