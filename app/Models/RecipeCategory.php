<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class RecipeCategory extends AggregateBaseModel {
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'updated_at',
        'created_at'
    ];

    public function recipe(): BelongsToMany {
        return $this->belongsToMany(
            Recipe::class,
            'recipe_category_recipe',
            'recipe_category_id',
            'recipe_id'
        );
    }
}
