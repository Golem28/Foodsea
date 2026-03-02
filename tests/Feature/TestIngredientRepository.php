<?php

namespace Tests\Feature;

use App\Domain\Ingredient\Ingredient;
use App\Domain\Ingredient\IngredientRepository;
use App\Domain\Ingredient\ValueObject\IngredientId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TestIngredientRepository extends TestCase {
    private IngredientRepository $repository;

    public function setUp(): void {
        parent::setUp();
        $this->repository = app(IngredientRepository::class);
    }

    /**
     * Test if ingredients can be saved and loaded
     */
    public function test_saveAndLoad(): void {
        $id = IngredientId::generateUniqueId();
        $ingredient = new Ingredient(
            $id,
            "Zucker"
        );

        $this->repository->save($ingredient);
        $loadedIngredient = $this->repository->load($id);

        $this->assertEquals($ingredient, $loadedIngredient);
    }
}
