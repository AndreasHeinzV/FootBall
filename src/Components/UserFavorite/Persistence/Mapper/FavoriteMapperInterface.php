<?php

namespace App\Components\UserFavorite\Persistence\Mapper;

use App\Components\UserFavorite\Persistence\DTO\FavoriteDTO;

interface FavoriteMapperInterface
{
    public function createFavoriteDTO(array $favoriteData): FavoriteDTO;
}