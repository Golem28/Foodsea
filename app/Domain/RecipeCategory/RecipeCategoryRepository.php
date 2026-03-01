<?php

namespace App\Domain\RecipeCategory;

use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;

interface RecipeCategoryRepository {
    public function loadAll(): array;
    public function load(RecipeCategoryId $id): RecipeCategory;
    public function save(RecipeCategory $recipe): bool;
    public function delete(RecipeCategoryId $id): bool;
}
