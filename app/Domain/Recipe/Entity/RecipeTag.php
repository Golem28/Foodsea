<?php

namespace App\Domain\Recipe\Entity;

use App\Domain\Recipe\ValueObject\RecipeTagId;

class RecipeTag {
    public function __construct(
        private RecipeTagId $id
    ) {

    }
}
