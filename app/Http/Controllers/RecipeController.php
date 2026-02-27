<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Application\RecipeRepositoryImplemenation;

use App\ChefkochAPI\DBRecepeFetcher;
use Nette\NotImplementedException;
use RecipeRepository;

class RecipeController extends Controller {
    private $lastRecipeId = 798371183473952;
    private RecipeRepositoryImplemenation $repository;

    public function __construct() {
        $this->repository = new RecipeRepositoryImplemenation();
    }

    public function show($id) {
        return $this->repository->get($id);
    }

    public function index() {
        throw new NotImplementedException("Implement the listing of recipes");
    }
}
