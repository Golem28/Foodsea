<?php

namespace App\Domain\RecipeCategory\Entity;

use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;

class RecipeCategory {
    public private(set) RecipeCategoryId $id;
    public private(set) $title;

    public function __construct(
        RecipeCategoryId $id,
        string $title
    ) {
        $this->id = $id;
        $this->title = $title;
    }
}
