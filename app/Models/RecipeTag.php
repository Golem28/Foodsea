<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipeTag extends Model {
    use HasFactory;

    protected $fillable = [
        'id',
        'tag',
        'updated_at',
        'created_at'
    ];

    public function recipes() {
        return $this->belongsToMany(
            Recipe::class,
            'recipe_tag_recipe',
            'recipe_tag_id',
            'recipe_id'
        );
    }
}
