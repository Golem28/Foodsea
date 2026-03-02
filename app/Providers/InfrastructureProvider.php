<?php

namespace App\Providers;

use App\Domain\Ingredient\IngredientRepository;
use App\Domain\Recipe\RecipeChefkochRepository;
use App\Domain\Recipe\RecipeRepository;
use App\Domain\RecipeCategory\RecipeCategoryRepository;
use App\Infrastructure\Database\IngredientRepositoryImplementation;
use App\Infrastructure\Database\RecipeCategoryRepositoryImplementation;
use App\Infrastructure\Database\RecipeRepositoryImplemenation;
use App\Infrastructure\Http\ChefkochRepositoryImplementation;
use Illuminate\Support\ServiceProvider;

class InfrastructureProvider extends ServiceProvider {
    public $bindings = [
        RecipeChefkochRepository::class => ChefkochRepositoryImplementation::class,
        RecipeCategoryRepository::class => RecipeCategoryRepositoryImplementation::class,
        RecipeRepository::class => RecipeRepositoryImplemenation::class,
        IngredientRepository::class => IngredientRepositoryImplementation::class
    ];
}
