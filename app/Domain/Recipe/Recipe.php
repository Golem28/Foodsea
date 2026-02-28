<?php

namespace App\Domain\Recipe;

use App\Domain\Recipe\Entity\{
    RecipeTagList,
    IngredientList,
    RecipeTag,
    Ingredient,
};
use App\Domain\Recipe\ValueObject\{
    RecipeId,
    RecipeCategoryId,
    CookingTime,
    Difficulty,
    RecipeTagId,
    IngredientId,
    Url,
    UserRating,
};
use DateInterval;
use DateTime;

class Recipe {
    private array $recipeTags = [];
    private array $ingredients = [];

    public function __construct(
        private RecipeId $id,
        private RecipeCategoryId $categoryId,
        private string $title,
        private string $subtitle,
        private UserRating $rating,
        private CookingTime $cookingTime,
        private Difficulty $difficulty,
        private Url $image,
        private Url $originUrl,
        private DateTime $updatedAt,
        private DateTime $createdAt,
    ) {
    }

    public function addRecipeTag(RecipeTagId $id, string $name): void {
        $this->recipeTags[] = new RecipeTag($id, $name);
    }

    public function addIngredient(IngredientId $id, string $name): void {
        $this->ingredients[] = new Ingredient($id, $name);
    }

    public function getId(): RecipeId {
        return $this->id;
    }

    public function getCategoryId(): RecipeCategoryId {
        return $this->categoryId;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getSubtitle(): string {
        return $this->subtitle;
    }

    public function getRating(): UserRating {
        return $this->rating;
    }

    public function getCookingTime(): DateInterval {
        return $this->cookingTime->getCookingTime();
    }

    public function getRestingTime(): DateInterval {
        return $this->cookingTime->getRestingTime();
    }

    public function getDifficulty(): Difficulty {
        return $this->difficulty;
    }

    public function getImage(): Url {
        return $this->image;
    }

    public function getOriginUrl(): Url {
        return $this->originUrl;
    }

    public function getUpdatedAt(): DateTime {
        return $this->updatedAt;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    public function getRecipeTagIds(): array {
        return array_map(fn(RecipeTag $tag) => $tag->getId(), $this->recipeTags);
    }

    public function getIngredientIds(): array {
        return array_map(fn(Ingredient $ingredient) => $ingredient->getId(), $this->ingredients);
    }
}
