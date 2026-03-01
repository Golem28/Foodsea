<?php

namespace Tests\Feature;

use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeRepository;
use App\Domain\Recipe\ValueObject\CookingTime;
use App\Domain\Recipe\ValueObject\RecipeId;
use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;
use App\Infrastructure\Database\RecipeRepositoryImplemenation;
use DateInterval;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Metadata\CoversFunction;
use Tests\TestCase;

class TestRecipeRepositoryImplemenation extends TestCase {
    /**
     * Test if a recipe can be saved and loaded again
     */
    #[CoversFunction('save')]
    #[CoversFunction('load')]
    public function test_example(): void {
        $repository = new RecipeRepositoryImplemenation();

        $now = new DateTimeImmutable();
        $now = $now->setTime(
            (int) $now->format('H'),
            (int) $now->format('i'),
            (int) $now->format('s'),
            0
        );

        $recipe = new Recipe(
            RecipeId::generateUniqueId(),
            RecipeCategoryId::generateUniqueId(),
            'Test Recipe',
            'Test Subtitle',
            new CookingTime(
                new DateInterval('PT30M'),
                new DateInterval('PT45M')
            ),
            $now,
            $now
        );
        $recipe->addRecipeTag('under_test');
        $recipe->addRecipeTag('heavy_cream');

        $repository->save($recipe);
        $loadedRecipe = $repository->load($recipe->id);

        $this->assertEquals($recipe, $loadedRecipe);
    }
}
