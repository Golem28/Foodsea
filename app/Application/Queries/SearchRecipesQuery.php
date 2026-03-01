<?php

namespace App\Application\Queries;

use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeRepository;
use App\Domain\Recipe\ValueObject\RecipeFilter;
use App\Domain\Recipe\ValueObject\RecipeId;
use Spatie\LaravelIgnition\Recorders\QueryRecorder\Query;

class SearchRecipesQuery extends Query {
    public function __construct(
        private RecipeRepository $repository
    ) {
    }

    public function execute(RecipeFilter $request): array {
        return $this->repository->search($request);
    }
}
