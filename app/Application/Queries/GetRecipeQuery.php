<?php

namespace App\Application\Queries;

use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeRepository;
use App\Domain\Recipe\ValueObject\RecipeId;
use Spatie\LaravelIgnition\Recorders\QueryRecorder\Query;

class GetRecipeQuery extends Query {
    public function __construct(
        private RecipeRepository $repository
    ) {
    }

    public function execute(RecipeId $request): Recipe {
        return $this->repository->load($request);
    }
}
