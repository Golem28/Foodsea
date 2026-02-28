<?php

namespace App\Domain\RecipeCategory;

use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;

class RecipeCategory {
    public private(set) RecipeCategoryId $id;
    public private(set) $title;
    public private(set) ?RecipeCategoryId $parentId;

    public function __construct(
        RecipeCategoryId $id,
        string $title,
        ?RecipeCategoryId $parentId
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->parentId = $parentId;
    }
}
