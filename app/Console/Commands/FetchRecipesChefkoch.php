<?php

namespace App\Console\Commands;

use App\Domain\Recipe\ChefkochRecipeRepository;
use App\Domain\Recipe\RecipeRepository;
use App\Domain\RecipeCategory\ChefkochRecipeCategoryRepository;
use App\Domain\RecipeCategory\RecipeCategoryRepository;
use Illuminate\Console\Command;

class FetchRecipesChefkoch extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch_recipes_chefkoch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads more commands from the chefkoch api';

    protected ChefkochRecipeRepository $recipeChefkochRepository;
    protected ChefkochRecipeCategoryRepository $categoryChefkochRepository;
    protected RecipeRepository $recipeRepository;
    protected RecipeCategoryRepository $recipeCategoryRepository;

    public function __construct() {
        parent::__construct();
        $this->recipeChefkochRepository = app(ChefkochRecipeRepository::class);
        $this->categoryChefkochRepository = app(ChefkochRecipeCategoryRepository::class);
        $this->recipeRepository = app(RecipeRepository::class);
        $this->recipeCategoryRepository = app(RecipeCategoryRepository::class);
    }

    /**
     * Execute the console command.
     */
    public function handle() {
        $this->syncRecipeCategories();
    }

    public function syncRecipeCategories() {
        $categories = $this->categoryChefkochRepository->getCategories();

        if ($categories->isSuccess() === false) {
            $this->error("Failed to fetch recipe IDs: " . $categories->getError());
            return;
        }

        foreach ($categories->getData() as $category) {
            var_dump("Save category", $category);
            $this->recipeCategoryRepository->save($category);
        }
    }

    public function syncRecipes() {
        $responseRecipeIds = $this->recipeChefkochRepository->getRecipeIds();

        if ($responseRecipeIds->isSuccess() === false) {
            $this->error("Failed to fetch recipe IDs: " . $responseRecipeIds->getError());
            return;
        }

        $chefkochIds = $responseRecipeIds->getData();
        print_r($chefkochIds);

        foreach ($chefkochIds as $id) {
            $recipeData = $this->recipeChefkochRepository->getRecipe($id);
            if (!$recipeData->isSuccess()) {
                echo $recipeData->getError() . "\n";
                continue;
            }

            $recipe = $recipeData->getData();
        }
    }
}
