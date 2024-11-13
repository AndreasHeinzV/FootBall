<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Persistence\DTO;

readonly class FavoriteDTO
{
    public function __construct(
        public int $teamID,
        public string $teamName,
        public string $crest,
        public int $position,
    ) {
    }
}