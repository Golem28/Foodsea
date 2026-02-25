<?php

use App\Domain\Recipe\ValueObject\RecipeId;

interface RecipeRepository {
    public function get(RecipeId $id): Recipe;
    public function delete(RecipeId $id): bool;
}
