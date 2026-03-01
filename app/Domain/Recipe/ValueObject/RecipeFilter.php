<?php

namespace App\Domain\Recipe\ValueObject;

final readonly class RecipeFilter {
    public array $tags;
    public array $ingredients;
    public ?string $name;
    public ?int $maxTotalTime;
    public ?int $minTotalTime;

    public function __construct(
        array $tags = [],
        array $ingredients = [],
        ?string $name = null,
        ?int $maxTotalTime = null,
        ?int $minTotalTime = null,
    ) {
        $this->tags = $tags;
        $this->ingredients = $ingredients;
        $this->name = $name;
        $this->maxTotalTime = $maxTotalTime;
        $this->minTotalTime = $minTotalTime;
    }
}
