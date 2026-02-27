<?php

namespace App\Application;

use Exception;

use App\Domain\Recipe\RecipeChefkochRepository;
use App\Infrastructure\Http\RecipeChefkochRepositoryImplementation;

class ChefkochAPI {
    private RecipeChefkochRepository $chefkochRepository;
    private RecipeRepositoryImplemenation $recipeRepository;

    public function __construct() {
        $this->chefkochRepository = new RecipeChefkochRepositoryImplementation();
        $this->recipeRepository = new RecipeRepositoryImplemenation();
    }

    public function getRecipe($id) {
        return $this->recipeRepository->get($id);
    }

    public function getCategories() {
        return $this->chefkochRepository->getCategories();
    }
}
