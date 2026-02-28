<?php

namespace App\Domain\Recipe\Entity;

use App\Domain\Recipe\ValueObject\RecipeTagId;
use stdClass;

class RecipeTag {
    public function __construct(
        private RecipeTagId $id,
        private string $name
    ) {
    }

    public function getId(): RecipeTagId {
        return $this->id;
    }
}
