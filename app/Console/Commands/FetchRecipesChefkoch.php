<?php

namespace App\Console\Commands;

use App\Domain\Recipe\RecipeChefkochRepository;
use App\Infrastructure\Http\ChefkochRepositoryImplementation;
use Illuminate\Console\Command;
use Nette\NotImplementedException;

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

    protected RecipeChefkochRepository $repository;

    public function __construct() {
        parent::__construct();
        $this->repository = new ChefkochRepositoryImplementation();
    }

    /**
     * Execute the console command.
     */
    public function handle() {
        $ids = $this->repository->getRecipeIds();

        if ($ids->isSuccess() === false) {
            $this->error("Failed to fetch recipe IDs: " . $ids->getError());
            return;
        }

        $chefkochIds = $ids->getData();
        print_r($chefkochIds);

        foreach ($chefkochIds as $id) {
            $recipeData = $this->repository->getRecipe($id);
            throw new NotImplementedException("Implement the saving of the recipe data to the database");
        }
    }
}
