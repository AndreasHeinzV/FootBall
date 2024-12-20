<?php

declare(strict_types=1);

namespace App\Components\Football\DTOs;

readonly class LeaguesDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $link
    ) {}
}