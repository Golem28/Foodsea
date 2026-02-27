<?php

namespace App\Console\Commands;

use App\Domain\Recipe\RecipeChefkochRepository;
use App\Infrastructure\Http\RecipeChefkochRepositoryImplementation;
use Illuminate\Console\Command;
use App\Application\RecipeRepositoryImplemenation;
use App\Models\Recipe;
use Nette\NotImplementedException;

class CrawlRecepies extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:crawl-recepies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads more commands from the chefkoch api';

    protected RecipeChefkochRepository $repository;

    public function __construct() {
        parent::__construct();
        $this->repository = new RecipeChefkochRepositoryImplementation();
    }

    /**
     * Execute the console command.
     */
    public function handle() {
        $ids = $this->repository->getRecipeIds();

        foreach ($ids->getData() as $id) {
            $recipeData = $this->repository->getRecipe($id);
            throw new NotImplementedException("Implement the saving of the recipe data to the database");
        }
    }
}
