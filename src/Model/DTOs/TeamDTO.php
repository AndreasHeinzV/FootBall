<?php

declare(strict_types=1);

namespace App\Model\DTOs;

readonly class TeamDTO
{

    public function __construct(
        public int $playerID,
        public string $link,
        public string $name,
    ) {
    }
}