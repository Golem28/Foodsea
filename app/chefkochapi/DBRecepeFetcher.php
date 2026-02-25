<?php
namespace App\ChefkochAPI;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\NeedsIngredient;
use App\Models\RecipeHasTag;
use App\Models\Tag;

require_once app_path() . '/chefkochapi/ChefkochAPI.php';
require_once app_path() . '/chefkochapi/Crawler/HttpClient.php';
use App\ChefkochAPI\Crawler\HttpClient;

class DBRecepeFetcher{

    public static function index()
    {
        return ChefkochAPI::get_crawler_for_categories();        
    }

    public static function get($id)
    {
        if (Recipe::where('id', '=', $id)->doesntExist()) {
            DBRecepeFetcher::make($id);
        }

        $recipe = 
            Recipe::where('id', '=', $id)
            ->first();

        $ingredients =
            NeedsIngredient::where('recipe_id', '=', $id)
            ->join('ingredient', 'ingredient.id', '=', 'needs_ingredient.ingredient_id')
            ->select('ingredient.name as ingredient_name', 'ingredient.id as ingredient_id')
            ->get();

        $tags = 
            RecipeHasTag::where('recipe_id', '=', $id)
            ->join('recipe_tag', 'recipe_tag.id', '=', 'recipe_has_tag.tag_id')
            ->select('recipe_tag.tag as tag')
            ->get();
        
        $recipe->ingredients = $ingredients;
        $recipe->tags = $tags;

        return $recipe;
    }

    public static function makeMultiple($ids)
    {
        // Test for ids not in the database with one query
        $alreadin_ids = Recipe::select('id')->whereIn('id', $ids)->get();

        // Remove ids that are already in the database
        foreach ($alreadin_ids as $id) {
            if (($key = array_search($id->id, $ids)) !== false) {
                //print("Unset: " . $id->id . "<br/>");
                unset($ids[$key]);
            }
        }

        $max_load = 10;

        foreach ($ids as $id) {
            DBRecepeFetcher::make($id);
            if (--$max_load <= 0) {
                break;
            }
        }
    }

    private static function load_recipe($id){

    }

    public static function make($id)
    {
        $recipe = HttpClient::sendRequest(HttpClient::URL . $id); // Hier muss die API Abfrage rein
        
        if (!isset($recipe->id)) {
            print("Recipe not found " . $id . "<br/>");
            return;
        }

        $id = $recipe->id;

        // Set rating to 0 if not set
        if (!isset($recipe->rating)) {
            $recipe->rating = (object) [
                "rating" => 0,
                "numVotes" => 0
            ];
        }

        // Create recipe
        $recipeObj = Recipe::firstOrCreate([
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
            Ingredient::firstOrCreate([   
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
    }
}