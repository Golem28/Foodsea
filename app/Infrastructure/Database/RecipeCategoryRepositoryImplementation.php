<?php

namespace App\Infrastructure\Database;

use App\Domain\Common\Abstractions\EntityId;
use App\Domain\RecipeCategory\RecipeCategoryRepository;
use Illuminate\Support\Facades\DB;
use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;
use App\Domain\RecipeCategory\RecipeCategory;
use App\Models\RecipeCategory as RecipeCategoryModel;

class RecipeCategoryRepositoryImplementation implements RecipeCategoryRepository {
    public function load(RecipeCategoryId $id): RecipeCategory {
        $recipeCategoryData = RecipeCategoryModel::find($id);
        $parentId = $recipeCategoryData->parent_id ?
            new RecipeCategoryId($recipeCategoryData->parent_id) :
            null;

        $recipe = new RecipeCategory(
            new RecipeCategoryId($recipeCategoryData->id),
            $recipeCategoryData->name,
            $parentId,
            $recipeCategoryData->updated_at,
            $recipeCategoryData->created_at
        );

        return $recipe;
    }

    public function save(RecipeCategory $recipeCategory): bool {
        DB::transaction(function () use ($recipeCategory) {
            $parentId = $recipeCategory->parentId;
            if ($parentId instanceof EntityId) {
                $parentId = $parentId->getValue();
            }

            $recipeData = RecipeCategoryModel::updateOrCreate(
                [
                    'id' => $recipeCategory->id->getValue()
                ],
                [
                    'name' => $recipeCategory->title,
                    'parent_id' => $parentId,
                    'updated_at' => $recipeCategory->updatedAt,
                    'created_at' => $recipeCategory->createdAt
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
