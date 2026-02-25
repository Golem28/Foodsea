<?php

namespace App\Domain\Recipe;

use App\Domain\Recipe\Entity\{
    RecipeTagList,
    IngredientList
};
use App\Domain\Recipe\ValueObject\{
    RecipeId,
    RecipeCategoryId,
    CookingTime,
    Difficulty,
    Url
};
use DateTime;

class Recipe {
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
        private RecipeTagList $recipeTags,
        private IngredientList $ingredients
    ) {
    }
}
