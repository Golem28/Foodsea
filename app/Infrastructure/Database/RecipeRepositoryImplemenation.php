<?php

namespace App\Infrastructure\Database;

use Illuminate\Support\Facades\DB;
use App\Domain\RecipeCategory\ValueObject\RecipeCategoryId;
use App\Domain\Ingredient\ValueObject\IngredientId;
use App\Domain\Recipe\{
    RecipeRepository,
    Recipe,
    ValueObject\RecipeId,
    ValueObject\CookingTime,
};
use App\Models\Recipe as RecipeModel;

class RecipeRepositoryImplemenation implements RecipeRepository {
    public function load(RecipeId $id): Recipe {
        $recipeData = RecipeModel::find($id);
        $recipe = new Recipe(
            new RecipeId($recipeData->id),
            new RecipeCategoryId($recipeData->category_id),
            $recipeData->title,
            $recipeData->subtitle,
            new CookingTime(
                $recipeData->preparation_time,
                $recipeData->resting_time
            ),
            $recipeData->updated_at,
            $recipeData->created_at
        );

        $ingredientsData = $recipeData->ingredients()->get();
        foreach ($ingredientsData as $ingredient) {
            $recipe->addIngredient(new IngredientId($ingredient->ingredient_id));
        }

        $tagsData = $recipeData->tags()->get();
        foreach ($tagsData as $tag) {
            $recipe->addRecipeTag($tag->tag);
        }

        return $recipe;
    }

    public function save(Recipe $recipe): bool {
        DB::transaction(function () use ($recipe) {
            $recipeData = RecipeModel::updateOrCreate(
                [
                    'id' => $recipe->id->getValue()
                ],
                [
                    'category_id' => $recipe->categoryId->getValue(),
                    'title' => $recipe->title,
                    'subtitle' => $recipe->subtitle,
                    'preparation_time' => $recipe->getPreparationTime(),
                    'resting_time' => $recipe->getRestingTime(),
                    'updated_at' => $recipe->updatedAt,
                ]
            );

            // Sync ingredients
            $ingredientIds = array_map(
                fn($id) => $id->getValue(),
                $recipe->getIngredientIds()
            );
            $recipeData->ingredients()->sync($ingredientIds);

            // Sync Tags
            $newTags = array_map(
                fn($tag) => $tag->tag,
                $recipe->getRecipeTags()
            );
            $recipeData->tags()->whereNotIn('tag', $newTags)->delete();

            $existingTags = $recipeData->tags()->pluck('tag')->all();
            $tagsToAdd = array_diff($newTags, $existingTags);

            foreach ($tagsToAdd as $tag) {
                $recipeData->tags()->create(['tag' => $tag]);
            }
        });

        return true;
    }

    public function delete(RecipeId $id): bool {
        RecipeModel::where('id', '=', $id->getValue())->delete();
        return true;
    }
}
