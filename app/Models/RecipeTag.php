<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;

class RecipeTag extends Model {
    use HasFactory;

    #[Override]
    public $timestamps = false;

    protected $fillable = [
        'id',
        'tag',
        'updated_at',
        'created_at'
    ];

    public function recipes(): BelongsTo {
        return $this->belongsTo(Recipe::class);
    }
}
