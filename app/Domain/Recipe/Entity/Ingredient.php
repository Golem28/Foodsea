<?php

namespace App\Domain\Recipe\Entity;

use App\Domain\Recipe\ValueObject\IngredientId;

class Ingredient {
    public function __construct(
        private IngredientId $id,
        private string $name
    ) {
    }

    public function getId(): IngredientId {
        return $this->id;
    }
}
