<?php

namespace App\Domain\Recipe\Entity;

use App\Domain\Recipe\ValueObject\RecipeCategoryId;

class RecipeCategory {
    public function __construct(
        private RecipeCategoryId $id,
    ) {

    }
}
