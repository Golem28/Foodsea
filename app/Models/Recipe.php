<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipe extends AggregateBaseModel {
    use HasFactory;

    protected $fillable = [
        'id',
        'type',
        'title',
        'subtitle',
        'rating',
        'numVotes',
        'difficulty',
        'viewCount',
        'cookingTime',
        'restingTime',
        'totalTime',
        'previewImageUrlTemplate',
        'siteUrl',
        'updated_at',
        'created_at'
    ];

    public function favouriteByUsers(): BelongsToMany {
        return $this->belongsToMany(
            User::class,
            'user_favourite_recipe',
            'recipe_id',
            'user_id'
        );
    }

    public function ingredients(): BelongsToMany {
        return $this->belongsToMany(
            Ingredient::class,
            'recipe_ingredient',
            'recipe_id',
            'ingredient_id'
        );
    }

    public function tags(): BelongsToMany {
        return $this->belongsToMany(
            RecipeTag::class,
            'recipe_tag_recipe',
            'recipe_id',
            'recipe_tag_id'
        );
    }

    public function categories(): BelongsToMany {
        return $this->belongsToMany(
            RecipeCategory::class,
            'recipe_category_recipe',
            'recipe_id',
            'recipe_category_id'
        );
    }
}
