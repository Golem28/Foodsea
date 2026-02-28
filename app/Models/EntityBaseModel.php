<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Override;

abstract class EntityBaseModel extends Model {
    use HasFactory;

    #[Override]
    protected $primaryKey = 'id';

    #[Override]
    public $incrementing = false;

    #[Override]
    protected $keyType = 'string';

    #[Override]
    public $timestamps = false;
}
