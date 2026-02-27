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
};
use DateTime;

class Recipe {
    private array $recipeTags;
    private array $ingredients;

    public function __construct(
        private RecipeId $id,
        private RecipeCategoryId $categoryId,
        private string $title,
        private string $subtitle,
        private UserRating $rating,
        private CookingTime $cookingTime,
        private Difficulty $difficulty,
        private DateTime $updatedAt,
        private DateTime $createdAt,
        private Url $image,
        private Url $originUrl,
        RecipeTagList $recipeTags,
        IngredientList $ingredients
    ) {
        $this->recipeTags = $recipeTags->getList();
        $this->ingredients = $ingredients->getList();
    }

    public function addRecipeTag(string $name): void {
        $this->recipeTags[] = new RecipeTag(RecipeTagId::generateUniqueId(), $name);
    }

    public function addIngredient(string $name): void {
        $this->ingredients[] = new Ingredient(IngredientId::generateUniqueId(), $name);
    }
}
