<?php

namespace App\Infrastructure;

class RecipeRepository {
    public function get(int $id) {
        return Recipe::where('id', '=', $id)->first();
    }


}
