<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecipeCategory extends AggregateBaseModel {
    use HasFactory;

    public function __construct(array $attributes = []) {
        $this->fillable = array_merge(
            parent::getFillable(),
            [
                'name',
                'parent_id',
            ]
        );

        parent::__construct($attributes);
    }

    public function recipes(): BelongsToMany {
        return $this->belongsToMany(
            Recipe::class,
            'recipe_categories_recipe',
            'recipe_id',
            'recipe_category_id'
        );
    }

    public function getSubcategories() {
        return $this->hasMany(RecipeCategory::class, 'parent_id');
    }
}
