<?php

namespace App\Application\Common\Abstractions;

interface Query {
    public function execute(mixed $request): mixed;
}
