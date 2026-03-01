<?php

namespace App\Infrastructure\Database;

use App\Domain\Recipe\ValueObject\RecipeFilter;
use DateInterval;
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
use stdClass;

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
                    'category_id' => $recipe->categoryId->getValue(),
                    'title' => $recipe->title,
                    'subtitle' => $recipe->subtitle,
                    'preparation_time' => $recipe->getPreparationTime()->format('P%yY%mM%dDT%hH%iM%sS'),
                    'resting_time' => $recipe->getRestingTime()->format('P%yY%mM%dDT%hH%iM%sS'),
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
            $query->where('totalTime', '<=', $filter->maxTotalTime);
        if ($filter->minTotalTime)
            $query->where('totalTime', '>=', $filter->minTotalTime);

        // Eager load everything
        $recipesData = $query->with(['ingredients', 'tags'])->take(100)->get();
        return $recipesData->map(fn($data) => $this->mapDataToDomainModel($data))->toArray();
    }

    private function mapDataToDomainModel(\App\Models\Recipe $recipeData): Recipe {
        $recipe = new Recipe(
            new RecipeId($recipeData->id),
            new RecipeCategoryId($recipeData->category_id),
            $recipeData->title,
            $recipeData->subtitle,
            new CookingTime(
                new DateInterval($recipeData->preparation_time),
                new DateInterval($recipeData->resting_time)
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

        return $recipe;
    }
}
