<?php

namespace Tests\Feature;

use App\Domain\Recipe\ChefkochRecipeRepository;
use App\Infrastructure\Http\ChefkochRepositoryImplementation;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(ChefkochRecipeRepository::class)]
class TestRecipeChefkochRepository extends TestCase {
    private ChefkochRecipeRepository $repository;

    public function setUp(): void {
        parent::setUp();
        $this->repository = app(ChefkochRecipeRepository::class);
    }

    /**
     * Test if recipe ids can be fetched from the repository
     */
    public function test_getRecipeIds(): void {
        $ids = $this->repository->getRecipeIds();

        $error = "Unknown";
        if (!$ids->isSuccess()) {
            $error = $ids->getError();
        }

        $this->assertTrue($ids->isSuccess(), $error);
        $this->assertCount(1000, $ids->getData());
    }

    public function test_getRecipe(): void {
        $recipeResponse = $this->repository->getRecipe(4404251764679628);

        $this->assertTrue($recipeResponse->isSuccess());
    }
}
