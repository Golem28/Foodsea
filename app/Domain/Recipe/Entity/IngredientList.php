<?php

namespace App\Domain\Recipe\Entity;

class IngredientList {
    private array $list;

    public function __construct(
        Ingredient ...$ingredients
    ) {
        $this->list = $ingredients;
    }

    public function getList(): array {
        return $this->list;
    }
}
