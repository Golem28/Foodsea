<?php

namespace App\Infrastructure\Database;

use App\Domain\Recipe\{
    Entity\RecipeTagList,
    Entity\IngredientList,
    Entity\RecipeTag,
    Entity\Ingredient,
    RecipeRepository,
    Recipe,
    ValueObject\RecipeCategoryId,
    ValueObject\RecipeId,
    ValueObject\CookingTime,
    ValueObject\Difficulty,
    ValueObject\Url,
    ValueObject\UserRating,
    ValueObject\IngredientId,
    ValueObject\RecipeTagId,
};
use App\Models\{
    Recipe as RecipeModel,
    Ingredient as IngredientModel,
    NeedsIngredient,
    RecipeHasTag,
    Tag,
};

class RecipeRepositoryImplemenation implements RecipeRepository {
    public function load(RecipeId $id): Recipe {
        $recipeData = RecipeModel::where('id', '=', $id)->first();
        $recipe = new Recipe(
            $id,
            RecipeCategoryId::generateUniqueId(), // TODO: Implement category loading
            $recipeData->title,
            $recipeData->subtitle,
            new UserRating($recipeData->rating, $recipeData->numVotes),
            new CookingTime($recipeData->cookingTime, $recipeData->restingTime),
            new Difficulty($recipeData->difficulty),
            new Url($recipeData->previewImageUrlTemplate),
            new Url($recipeData->siteUrl),
            $recipeData->updatedAt,
            $recipeData->createdAt,
        );

        $ingredientsData =
            NeedsIngredient::where('recipe_id', '=', $id)
                ->join('ingredient', 'ingredient.id', '=', 'needs_ingredient.ingredient_id')
                ->select('ingredient.name as ingredient_name', 'ingredient.id as ingredient_id')
                ->get();
        foreach ($ingredientsData as $ingredient) {
            $recipe->addIngredient(new IngredientId($ingredient->ingredient_id), $ingredient->ingredient_name);
        }

        $tagsData =
            RecipeHasTag::where('recipe_id', '=', $id)
                ->join('recipe_tag', 'recipe_tag.id', '=', 'recipe_has_tag.tag_id')
                ->select('recipe_tag.tag as tag')
                ->get();
        foreach ($tagsData as $tag) {
            $recipe->addRecipeTag(new RecipeTagId(0), $tag->tag);
        }

        return $recipe;
    }

    public function save(Recipe $recipe): bool {
        $id = $recipe->id;

        // Set rating to 0 if not set
        if (!isset($recipe->rating)) {
            $recipe->rating = (object) [
                "rating" => 0,
                "numVotes" => 0
            ];
        }

        // Create recipe
        $recipeObj = RecipeModel::firstOrCreate([
            "id" => $id,
            "type" => $recipe->type,
            "title" => $recipe->title,
            "subtitle" => $recipe->subtitle,
            "rating" => $recipe->rating->rating,
            "numVotes" => $recipe->rating->numVotes,
            "difficulty" => $recipe->difficulty,
            "viewCount" => $recipe->viewCount,
            "cookingTime" => $recipe->cookingTime,
            "restingTime" => $recipe->restingTime,
            "totalTime" => $recipe->totalTime,
            "previewImageUrlTemplate" => $recipe->previewImageUrlTemplate,
            "siteUrl" => $recipe->siteUrl]);

        // Get all ingredients 
        $ingredients = [];

        foreach ($recipe->ingredientGroups as $ingredientGroup) {
            foreach ($ingredientGroup->ingredients as $ingredient) {
                $ingredients[] = $ingredient;
            }
        }

        // Fetch all ingredients to database
        foreach ($ingredients as $ingredient) {
            IngredientModel::firstOrCreate([
                "id" => $ingredient->id,
                "name" => $ingredient->name]);

            NeedsIngredient::firstOrCreate([
                "recipe_id" => $id,
                "ingredient_id" => $ingredient->id]);
        }

        $tags = $recipe->tags;

        foreach ($tags as $tag) {
            $tagObj = Tag::firstOrCreate([
                "tag" => $tag]);

            RecipeHasTag::firstOrCreate([
                "recipe_id" => $id,
                "tag_id" => $tagObj->id]);
        }

        DB::transaction(function () use ($recipe) {
            // Save recipe
            $recipeObj = RecipeModel::updateOrCreate([
                "id" => $recipe->getId()->getValue(),
            ], [
                "type" => $recipe->getCategoryId()->getValue(),
                "title" => $recipe->getTitle(),
                "subtitle" => $recipe->getSubtitle(),
                "rating" => $recipe->getRating(),
                "numVotes" => $recipe->getRating(),
                "difficulty" => $recipe->getDifficulty(),
                "viewCount" => $recipe->getViewCount(),
                "cookingTime" => $recipe->getCookingTime(),
                "restingTime" => $recipe->getRestingTime(),
                "previewImageUrlTemplate" => $recipe->getImage(),
                "siteUrl" => $recipe->getOriginUrl()
            ]);

            foreach ($recipe->getIngredientIds() as $ingredientGroup) {
                foreach ($ingredientGroup->getIngredients() as $ingredient) {
                    IngredientModel::firstOrCreate([
                        "id" => $ingredient->getId()->getValue(),
                        "name" => $ingredient->getName()]);
                }
            }

            foreach ($recipe->getTags() as $tag) {
                $tagObj = Tag::firstOrCreate([
                    "tag" => $tag]);
                RecipeHasTag::firstOrCreate([
                    "recipe_id" => $id,
                    "tag_id" => $tagObj->id]);
            }
        });
    }

    public function delete(RecipeId $id): bool {
        RecipeModel::where('id', '=', $id)->delete();
        NeedsIngredient::where('recipe_id', '=', $id)->delete();
        RecipeHasTag::where('recipe_id', '=', $id)->delete();
        return true;
    }
}
