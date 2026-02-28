<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecipeCategory extends AggregateBaseModel {
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'updated_at',
        'created_at'
    ];

    public function recipe(): HasMany {
        return $this->hasMany(Recipe::class, 'category_id');
    }

    public function getSubcategories() {
        return $this->hasMany(RecipeCategory::class, 'parent_id');
    }
}
