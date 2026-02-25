<?php
namespace App\ChefkochAPI;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

require_once 'DBRecepeFetcher.php';
use App\ChefkochAPI\DBRecepeFetcher;

class FluentRecepeFilterer {
    private $recipes;
    private $tags = [];
    private $ingredients = [];

    public function __construct() {
        //DB::enableQueryLog();
        $this->recipes = DB::table('recipe')->inRandomOrder()
            ->select('recipe.id', 'recipe.title', 'recipe.subtitle', 'recipe.rating', 'recipe.difficulty', 'recipe.totalTime', 'recipe.siteUrl', 'recipe.previewImageUrlTemplate');
    }

    public function filter_ingredient($ingredient) {
        $this->ingredients[] = $ingredient;
        return $this;
    }

    public function filter_ingredients($ingredients) {
        $this->ingredients = array_merge($this->ingredients, $ingredients);
        return $this;
    }

    public function filter_tag($tag) {
        $this->tags[] = $tag;
        return $this;
    }

    public function filter_tags($tags) {
        $this->tags = array_merge($this->tags, $tags);
        return $this;
    }

    public function filter_name($name) {
        $this->recipes->where('title', 'like', '%' . $name . '%');
        return $this;
    }

    public function filter_rating($rating) {
        $this->recipes->where('rating', '>=', $rating);
        return $this;
    }

    public function filter_difficulty($difficulty) {
        $this->recipes->where('difficulty', '=', $difficulty);
        return $this;
    }

    public function filter_time($time) {
        $this->recipes->where('totalTime', '<=', $time);
        return $this;
    }

    public function filter_min_time($time) {
        $this->recipes->where('totalTime', '>=', $time);
        return $this;
    }

    public function filter_cooking_time($time) {
        $this->recipes->where('cookingTime', '<=', $time);
        return $this;
    }

    public function filter_resting_time($time) {
        $this->recipes->where('restingTime', '<=', $time);
        return $this;
    }

    public function filter_view_count($count) {
        $this->recipes->where('viewCount', '>=', $count);
        return $this;
    }

    public function filter_popularity_score($score) {
        $this->recipes->whereRaw('viewCount * rating >= ?', [$score]);
        return $this;
    }

    private function filter_for_tags() {
        // Connects the ingredients with like and or
        // Filters if the recipe has all the ingredients

        if (count($this->tags) > 0) {
            $this->recipes->join('recipe_has_tag', 'recipe.id', '=', 'recipe_has_tag.recipe_id')
                ->join('recipe_tag', 'recipe_tag.id', '=', 'recipe_has_tag.tag_id')
                ->where(function ($query) {
                    $query->where('recipe_tag.tag', 'LIKE', '%' . $this->tags[0] . '%');

                    for ($i = 1; $i < count($this->tags); $i++) {
                        $query->orWhere('recipe_tag.tag', 'LIKE', '%' . $this->tags[$i] . '%');
                    }
                })
                ->groupBy('recipe.id', 'recipe.title', 'recipe.subtitle', 'recipe.rating', 'recipe.difficulty', 'recipe.totalTime', 'recipe.siteUrl', 'recipe.previewImageUrlTemplate', 'isFavourite')
                ->havingRaw('COUNT(recipe.id) = ?', [count($this->tags)]);
        }
    }

    public function filter_ids($ids) {
        DBRecepeFetcher::makeMultiple($ids);
        $this->recipes->whereIn('recipe.id', $ids);
        return $this;
    }

    private function filter_for_ingredients() {
        if (count($this->ingredients) > 0) {
            $this->recipes->join('needs_ingredient', 'recipe.id', '=', 'needs_ingredient.recipe_id')
                ->join('ingredient', 'ingredient.id', '=', 'needs_ingredient.ingredient_id')
                ->where(function ($query) {
                    $query->where('ingredient.name', 'LIKE', '%' . $this->ingredients[0] . '%');

                    for ($i = 1; $i < count($this->ingredients); $i++) {
                        $query->orWhere('ingredient.name', 'LIKE', '%' . $this->ingredients[$i] . '%');
                    }
                })
                ->groupBy('recipe.id', 'recipe.title', 'recipe.subtitle', 'recipe.rating', 'recipe.difficulty', 'recipe.totalTime', 'recipe.siteUrl', 'recipe.previewImageUrlTemplate', 'isFavourite')
                ->havingRaw('COUNT(recipe.id) = ?', [count($this->ingredients)]);
        }
    }

    /*
    Add a variable to the query that indicates if the recipe is a favourite

    Left join the table favourite, that is filtered 
    */
    private function add_favourites() {
        if (!Auth::check()) {
            return;
        }
        $this->recipes->leftJoin('favourite', 'recipe.id', '=', 'favourite.recipe_id')
            ->where(function ($query) {
                $query->where('favourite.user_id', '=', auth()->user()->id);
                $query->orWhere('favourite.user_id', '=', null);
            })
            ->addSelect(DB::raw('IF(favourite.recipe_id IS NULL, 0, 1) AS isFavourite'));
    }

    public function get_recipes() {
        // Join tables
        $this->filter_for_tags();
        $this->filter_for_ingredients();
        $this->add_favourites();

        // Get image, name, rating, difficulty, time and site url
        return $this->recipes->take(100)->get();
    }
}