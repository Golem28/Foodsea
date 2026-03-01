<?php

namespace Tests\Feature;

use App\Domain\Recipe\Recipe;
use App\Domain\Recipe\ValueObject\CookingTime;
use App\Domain\Recipe\ValueObject\RecipeId;
use App\Domain\RecipeCategory\RecipeCategory;
use App\Domain\RecipeCategory\RecipeCategoryRepository;
use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;
use App\Infrastructure\Database\RecipeCategoryRepositoryImplementation;
use App\Infrastructure\Database\RecipeRepositoryImplemenation;
use DateInterval;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(RecipeCategoryRepository::class)]
class TestRecipeCategoryRepository extends TestCase {
    /**
     * Test if a recipe can be saved and loaded again
     */
    #[Test]
    public function test_save_and_load_recipe(): void {
        $repository = new RecipeCategoryRepositoryImplementation();

        $now = new DateTimeImmutable();
        $now = $now->setTime(
            (int) $now->format('H'),
            (int) $now->format('i'),
            (int) $now->format('s'),
            0
        );

        $recipeCategory = new RecipeCategory(
            RecipeCategoryId::generateUniqueId(),
            'Main Dish',
            null,
            $now,
            $now
        );

        $repository->save($recipeCategory);
        $loadedRecipeCategory = $repository->load($recipeCategory->id);

        $this->assertEquals($recipeCategory, $loadedRecipeCategory);
    }
}
