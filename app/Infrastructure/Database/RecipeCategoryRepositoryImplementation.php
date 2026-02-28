<?php

namespace App\Infrastructure\Database;

use Illuminate\Support\Facades\DB;
use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;
use App\Domain\RecipeCategory\RecipeCategory;
use App\Models\RecipeCategory as RecipeCategoryModel;

class RecipeCategoryRepositoryImplementation {
    public function load(RecipeCategoryId $id): RecipeCategory {
        $recipeCategoryData = RecipeCategoryModel::find($id);
        $recipe = new RecipeCategory(
            new RecipeCategoryId($recipeCategoryData->id),
            $recipeCategoryData->title,
            new RecipeCategoryId($recipeCategoryData->parent_id)
        );

        return $recipe;
    }

    public function save(RecipeCategory $recipeCategory): bool {
        DB::transaction(function () use ($recipeCategory) {
            $recipeData = RecipeCategoryModel::updateOrCreate(
                [
                    'id' => $recipeCategory->id->getValue()
                ],
                [
                    'name' => $recipeCategory->title,
                    'parent_id' => $recipeCategory->parentId->getValue()
                ]
            );
        });

        return true;
    }

    public function delete(RecipeCategoryId $id): bool {
        RecipeCategoryModel::where('id', '=', $id->getValue())->delete();
        return true;
    }
}
