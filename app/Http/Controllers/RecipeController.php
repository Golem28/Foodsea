<?php

namespace App\Http\Controllers;

use App\Application\Queries\GetRecipeQuery;
use Nette\NotImplementedException;

class RecipeController extends Controller {
    public function __construct(
        private GetRecipeQuery $getRecipeQuery
    ) {
    }

    public function show($id) {
        return $this->getRecipeQuery->execute($id);
    }

    public function index() {
        throw new NotImplementedException("Implement the listing of recipes");
    }
}
