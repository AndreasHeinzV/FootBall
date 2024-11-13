<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Persistence\Mapper;

use App\Components\UserFavorite\Persistence\DTO\FavoriteDTO;

class FavoriteMapper implements FavoriteMapperInterface
{
    public function createFavoriteDTO(array $favoriteData): FavoriteDTO
    {
        return new favoriteDTO(
            $favoriteData['teamID'],
            $favoriteData['teamName'],
            $favoriteData['crest'],
            $favoriteData['position']
        );
    }
}