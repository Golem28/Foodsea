<?php

namespace App\Infrastructure\Database;

use App\Domain\Recipe\ValueObject\RecipeFilter;
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
        return $this->mapDataToDomainModel($recipeData);
    }

    public function save(Recipe $recipe): bool {
        DB::transaction(function () use ($recipe) {
            $recipeData = RecipeModel::updateOrCreate(
                [
                    'id' => $recipe->id->getValue()
                ],
                [
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

            // Sync categories
            $categoryIds = array_map(
                fn($id) => $id->getValue(),
                $recipe->getCategoryIds()
            );
            $recipeData->categories()->sync($categoryIds);

            // Sync Tags
            $newTags = array_map(
                fn($tag) => $tag->name,
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

    public function search(RecipeFilter $filter): array {
        $query = RecipeModel::query();

        if ($filter->name) {
            $query->where('title', 'like', "%{$filter->name}%");
        }

        if (!empty($filter->ingredients)) {
            foreach ($filter->ingredients as $ingredient) {
                $query->whereHas('ingredients', fn($q) => $q->where('name', 'like', "%{$ingredient}%"));
            }
        }

        if (!empty($filter->tags)) {
            foreach ($filter->tags as $tag) {
                $query->whereHas('tags', fn($q) => $q->where('tag', 'like', "%{$tag}%"));
            }
        }

        if ($filter->maxTotalTime)
            $query->whereRaw('preparation_time + resting_time <= ?', [$filter->maxTotalTime]);
        if ($filter->minTotalTime)
            $query->whereRaw('preparation_time + resting_time >= ?', [$filter->minTotalTime]);

        // Eager load everything
        $recipesData = $query->with(['ingredients', 'tags'])->get();
        return $recipesData->map(fn($data) => $this->mapDataToDomainModel($data))->toArray();
    }

    private function mapDataToDomainModel(\App\Models\Recipe $recipeData): Recipe {
        $recipe = new Recipe(
            new RecipeId($recipeData->id),
            $recipeData->title,
            $recipeData->subtitle,
            new CookingTime(
                $recipeData->preparation_time,
                $recipeData->resting_time
            ),
            $recipeData->updated_at,
            $recipeData->created_at
        );

        foreach ($recipeData->ingredients as $ingredient) {
            $recipe->addIngredient(new IngredientId($ingredient->id));
        }

        foreach ($recipeData->tags as $tag) {
            $recipe->addRecipeTag($tag->tag);
        }

        foreach ($recipeData->categories as $category) {
            $recipe->addCategory(new RecipeCategoryId($category->id));
        }

        return $recipe;
    }
}
