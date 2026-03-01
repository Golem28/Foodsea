<?php

namespace App\Domain\Recipe;

use App\Domain\Common\Responses\FetchIdResponse;
use App\Domain\Recipe\Responses\FetchRecipeResponse;

interface RecipeChefkochRepository {
    public function getRecipe(
        int $id,
    ): FetchRecipeResponse;

    public function searchRecipeIds(string $userQuery): FetchIdResponse;

    public function getRecipeIds(): FetchIdResponse;
}
