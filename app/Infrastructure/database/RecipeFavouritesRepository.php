<?php

namespace App\Infrastructure;

class RecipeFavouritesRepository {
    public function get(int $userId): array {
        return DB::table("favourite")->where("user_id", $userId)->pluck('recipe_id');
    }

    public function create(int $recipeId, int $userId): void {
        Favourite::firstOrCreate([
            "recipe_id" => $recipeId,
            "user_id" => $userId,
        ]);
    }

    public function delete(int $recipeId, int $userId) {
        $favourite = Favourite::where([
            "recipe_id" => $recipeId,
            "user_id" => $userId])->first();

        if ($favourite != null) {
            $favourite->delete();
        }
    }
}
