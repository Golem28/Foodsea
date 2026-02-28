<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

abstract class AggregateBaseModel extends EntityBaseModel {
    use HasFactory;

    #[Override]
    public $timestamps = true;
}
