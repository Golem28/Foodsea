<?php

namespace App\Domain\RecipeCategory;

use App\Domain\RecipeCategory\Responses\FetchRecipeCategoriesResponse;

interface ChefkochRecipeCategoryRepository {
    public function getCategories(): FetchRecipeCategoriesResponse;
}
