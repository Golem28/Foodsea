<?php

namespace Tests\Feature;

use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\RecipeRepository;
use App\Domain\Recipe\ValueObject\CookingTime;
use App\Domain\Recipe\ValueObject\RecipeId;
use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(RecipeRepository::class)]
class TestRecipeRepository extends TestCase {
    private RecipeRepository $repository;

    public function setUp(): void {
        parent::setUp();
        $this->repository = app(RecipeRepository::class);
    }

    /**
     * Test if a recipe can be saved and loaded again
     */
    #[Test]
    public function test_save_and_load_recipe(): void {
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
                30,
                45
            ),
            $now,
            $now
        );
        $recipe->addRecipeTag('under_test');
        $recipe->addRecipeTag('heavy_cream');

        $this->repository->save($recipe);
        $loadedRecipe = $this->repository->load($recipe->id);

        $this->assertEquals($recipe, $loadedRecipe);
    }
}
