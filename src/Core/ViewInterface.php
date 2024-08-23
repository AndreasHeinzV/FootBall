<?php

declare(strict_types=1);

namespace App\Core;

interface ViewInterface
{
    public function addParameter(string $key, mixed $value): void;

    public function display(): void;

}