<?php

declare(strict_types=1);

namespace App\Model\Mapper;

use App\Model\DTOs\FavoriteDTO;

class FavoriteMapper
{
    public function createFavoriteDTO(array $favoriteData): FavoriteDTO
    {
        return new favoriteDTO(
            $favoriteData['teamID'],
            $favoriteData['teamName'],
            $favoriteData['crest']
        );
    }
}