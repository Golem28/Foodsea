<?php

namespace App\Domain\Recipe\Responses;

use App\Domain\Common\Abstractions\ResponseBase;
use App\Domain\Recipe\Recipe;

class FetchRecipeResponse extends ResponseBase {
    public function __construct(
        private string|null $error = null,
        Recipe $data,
    ) {
        parent::__construct($data, $error);
    }

    public function getData(): Recipe {
        return parent::getData();
    }
}
