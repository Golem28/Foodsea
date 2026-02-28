<?php

namespace App\Domain\Recipe;

use App\Domain\Recipe\ValueObject\RecipeId;

interface RecipeRepository {
    public function load(RecipeId $id): Recipe;
    public function save(Recipe $recipe): bool;
    public function delete(RecipeId $id): bool;
}
