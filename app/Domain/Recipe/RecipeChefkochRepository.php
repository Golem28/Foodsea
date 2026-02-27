<?php

namespace App\Domain\Recipe;

use App\Domain\Common\Responses\FetchIdResponse;

interface RecipeChefkochRepository {
    public function getRecipe(
        int $id,
    ): array|null;

    public function searchRecipeIds(string $userQuery): FetchIdResponse;

    public function getRecipeIds(): FetchIdResponse;

    public function getCategories(
    );
}
