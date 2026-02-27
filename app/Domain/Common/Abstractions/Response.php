<?php

namespace App\Domain\Common\Abstractions;

interface Response {
    public function isSuccess(): bool;
    public function getData();
    public function getError();
}
