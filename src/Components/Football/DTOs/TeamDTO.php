<?php

declare(strict_types=1);

namespace App\Components\Football\DTOs;

readonly class TeamDTO
{

    public function __construct(
        /*
        public string $teamName,
        public int $teamID,
        public string $crest,
        */
        public int $playerID,
        public string $link,
        public string $name,
    ) {
    }
}