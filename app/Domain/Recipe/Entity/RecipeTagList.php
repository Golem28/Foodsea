<?php

namespace App\Domain\Recipe\Entity;

class RecipeTagList {
    private array $list;

    public function __construct(
        RecipeTag ...$tags
    ) {
        $this->list = $tags;
    }

    public function getList(): array {
        return $this->list;
    }
}
