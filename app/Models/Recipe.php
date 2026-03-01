<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recipe extends AggregateBaseModel {
    use HasFactory;

    protected $fillable = [
        'id',
        'category_id',
        'title',
        'subtitle',
        'preparation_time',
        'resting_time',
        'updated_at',
        'created_at'
    ];

    protected $casts = [
        'updated_at' => 'immutable_datetime',
        'created_at' => 'immutable_datetime'
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

    public function tags(): HasMany {
        return $this->hasMany(RecipeTag::class, 'recipe_id');
    }

    public function categories(): BelongsTo {
        return $this->belongsTo(RecipeCategory::class);
    }
}
