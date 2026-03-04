<?php

namespace App\Domain\RecipeCategory\Responses;

use App\Domain\Common\Abstractions\ResponseBase;
use App\Domain\RecipeCategory\RecipeCategory;

class FetchRecipeCategoriesResponse extends ResponseBase {
    public function __construct(
        private string|null $error = null,
        RecipeCategory ...$data,
    ) {

        parent::__construct($data, $error);
    }

    public function getData(): array {
        return parent::getData();
    }
}
