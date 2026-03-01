<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

abstract class AggregateBaseModel extends EntityBaseModel {
    use HasFactory;

    #[Override]
    public $timestamps = true;

    #[Override]
    protected $fillable = [
        'id',
        'updated_at',
        'created_at'
    ];

    protected $casts = [
        'updated_at' => 'immutable_datetime',
        'created_at' => 'immutable_datetime'
    ];
}
