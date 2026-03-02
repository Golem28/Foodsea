<?php

namespace App\Domain\Ingredient;

use App\Domain\Ingredient\ValueObject\IngredientId;

class Ingredient {
    public private(set) IngredientId $id;
    public private(set) string $name;

    public function __construct(
        IngredientId $id,
        string $name
    ) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): IngredientId {
        return $this->id;
    }
}
