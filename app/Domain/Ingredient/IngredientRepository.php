<?php

namespace App\Domain\Ingredient;

use App\Domain\Ingredient\ValueObject\IngredientId;

interface IngredientRepository {
    public function load(IngredientId $id): Ingredient;
    public function save(Ingredient $recipe): bool;
    public function delete(IngredientId $id): bool;
}
