<?php

namespace App\Infrastructure\Database;

use App\Domain\Ingredient\Ingredient;
use App\Domain\Ingredient\IngredientRepository;
use App\Domain\Ingredient\ValueObject\IngredientId;
use Illuminate\Support\Facades\DB;
use App\Models\Ingredient as IngredientModel;

class IngredientRepositoryImplementation implements IngredientRepository {
    public function load(IngredientId $id): Ingredient {
        $ingredientData = IngredientModel::find($id);
        return $this->mapDataToDomainModel($ingredientData);
    }

    public function save(Ingredient $ingredient): bool {
        DB::transaction(function () use ($ingredient) {
            IngredientModel::updateOrCreate(
                [
                    'id' => $ingredient->id->getValue()
                ],
                [
                    'name' => $ingredient->name,
                ]
            );
        });

        return true;
    }

    public function delete(IngredientId $id): bool {
        IngredientModel::where('id', '=', $id->getValue())->delete();
        return true;
    }

    private function mapDataToDomainModel(IngredientModel $ingredientData): Ingredient {
        $ingredient = new Ingredient(
            new IngredientId($ingredientData->id),
            $ingredientData->name,
        );

        return $ingredient;
    }
}
