<?php

namespace App\Domain\Recipe\ValueObject;

final readonly class RecipeFilter {
    public array $tags = [];
    public array $ingredients = [];
    public ?string $name = null;
    public ?int $maxTotalTime = null;
    public ?int $minTotalTime = null;

    public function __construct(
        array $tags = [],
        array $ingredients = [],
        ?string $name = null,
        ?string $difficulty = null,
        ?int $maxTotalTime = null,
        ?int $minTotalTime = null,
    ) {
        $this->tags = $tags;
        $this->ingredients = $ingredients;
        $this->name = $name;
        $this->difficulty = $difficulty;
        $this->maxTotalTime = $maxTotalTime;
        $this->minTotalTime = $minTotalTime;
    }
}
