<?php

namespace App\Domain\RecipeCategory\Entity;

use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;

class RecipeCategory {
    public function __construct(
        private RecipeCategoryId $id,
    ) {
    }
}
