<?php

declare(strict_types=1);

namespace App\Components\Football\DTOs;

readonly class CompetitionDTO
{

    public function __construct(
        public int $position,
        public string $name,
        public string $link,
        public int $playedGames,
        public int $won,
        public int $draw,
        public int $lost,
        public int $points,
        public int $goalsFor,
        public int $goalsAgainst,
        public int $goalDifference,
    ) {
    }
}