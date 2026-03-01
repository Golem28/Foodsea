<?php

namespace Tests\Feature;

use App\Domain\RecipeCategory\RecipeCategory;
use App\Domain\RecipeCategory\RecipeCategoryRepository;
use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;
use DateTimeImmutable;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(RecipeCategoryRepository::class)]
class TestRecipeCategoryRepository extends TestCase {
    private RecipeCategoryRepository $repository;

    public function setUp(): void {
        parent::setUp();
        $this->repository = app(RecipeCategoryRepository::class);
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

        $recipeCategory = new RecipeCategory(
            RecipeCategoryId::generateUniqueId(),
            'Main Dish',
            null,
            $now,
            $now
        );

        $this->repository->save($recipeCategory);
        $loadedRecipeCategory = $this->repository->load($recipeCategory->id);

        $this->assertEquals($recipeCategory, $loadedRecipeCategory);
    }
}
