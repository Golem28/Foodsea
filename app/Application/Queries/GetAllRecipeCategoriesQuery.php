<?php

namespace App\Application\Queries;

use App\Domain\RecipeCategory\RecipeCategoryRepository;
use Spatie\LaravelIgnition\Recorders\QueryRecorder\Query;

class GetAllRecipeCategoriesQuery extends Query {
    public function __construct(
        private RecipeCategoryRepository $repository
    ) {
    }

    public function execute(mixed $request): array {
        return $this->repository->loadAll();
    }
}
