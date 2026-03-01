<?php

namespace App\Domain\RecipeCategory;

use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;
use DateTimeImmutable;

class RecipeCategory {
    public private(set) RecipeCategoryId $id;
    public private(set) string $title;
    public private(set) ?RecipeCategoryId $parentId;
    public private(set) DateTimeImmutable $updatedAt;
    public private(set) DateTimeImmutable $createdAt;

    public function __construct(
        RecipeCategoryId $id,
        string $title,
        ?RecipeCategoryId $parentId,
        DateTimeImmutable $updatedAt,
        DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->parentId = $parentId;
        $this->updatedAt = $updatedAt;
        $this->createdAt = $createdAt;
    }
}
