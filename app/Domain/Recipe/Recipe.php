<?php

namespace App\Domain\Recipe;

use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;
use App\Domain\Ingredient\ValueObject\IngredientId;
use App\Domain\Recipe\ValueObject\{
    RecipeId,
    CookingTime,
    RecipeTag,
};
use DateInterval;
use DateTimeImmutable;

class Recipe {
    public private(set) RecipeId $id;
    public private(set) RecipeCategoryId $categoryId;
    public private(set) string $title;
    public private(set) string $subtitle;
    public private(set) DateTimeImmutable $updatedAt;
    public private(set) DateTimeImmutable $createdAt;
    private array $recipeTags = [];
    private array $ingredients = [];

    public function __construct(
        RecipeId $id,
        RecipeCategoryId $categoryId,
        string $title,
        string $subtitle,
        private CookingTime $cookingTime,
        DateTimeImmutable $updatedAt,
        DateTimeImmutable $createdAt,
    ) {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->updatedAt = $updatedAt;
        $this->createdAt = $createdAt;
    }

    public function addRecipeTag(string $name): void {
        $this->recipeTags[] = new RecipeTag($name);
    }

    public function addIngredient(IngredientId $id): void {
        $this->ingredients[] = $id;
    }

    public function getId(): RecipeId {
        return $this->id;
    }

    public function getPreparationTime(): int {
        return $this->cookingTime->getPreparationTime();
    }

    public function getRestingTime(): int {
        return $this->cookingTime->getRestingTime();
    }

    public function getRecipeTags(): array {
        return $this->recipeTags;
    }

    public function getIngredientIds(): array {
        return $this->ingredients;
    }
}
