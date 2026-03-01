<?php

namespace Tests\Feature;

use App\Domain\Recipe\RecipeChefkochRepository;
use App\Infrastructure\Http\ChefkochRepositoryImplementation;
use App\Models\Recipe;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(RecipeChefkochRepository::class)]
class TestRecipeChefkochRepository extends TestCase {
    /**
     * Test if recipe ids can be fetched from the repository
     */
    /*public function test_getRecipeIds(): void {
        $repository = new ChefkochRepositoryImplementation();

        $ids = $repository->getRecipeIds();

        $error = "Unknown";
        if (!$ids->isSuccess()) {
            $error = $ids->getError();
        }

        $this->assertTrue($ids->isSuccess(), $error);
        $this->assertCount(1000, $ids->getData());
    }*/

    public function test_getRecipe(): void {
        $repository = new ChefkochRepositoryImplementation();

        $recipeResponse = $repository->getRecipe(4404251764679628);

        $this->assertTrue($recipeResponse->isSuccess());
        var_dump($recipeResponse->getData());
    }
}
