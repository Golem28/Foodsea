<?php

namespace App\Domain\Common\Responses;

use App\Domain\Common\Abstractions\ResponseBase;

class FetchIdResponse extends ResponseBase {
    public function __construct(
        private string|null $error = null,
        string ...$data,
    ) {
        parent::__construct($data, $error);
    }

    public function getData(): array {
        return parent::getData();
    }
}
