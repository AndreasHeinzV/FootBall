<?php

namespace App\Components\UserFavorite\Persistence;

use App\Components\Database\Persistence\Entity\FavoriteEntity;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserFavorite\Persistence\DTO\FavoriteDTO;

interface UserFavoriteEntityManagerInterface
{
    public function saveUserFavorite(UserDTO $userDTO, FavoriteDTO $favoriteDTO): void;

    public function updateUserFavoritePosition(
        FavoriteEntity $favoriteEntity,
        FavoriteEntity $favoriteEntityChange,
        int $position,
        int $positionToChange,
    ): void;

    public function deleteUserFavorite(UserDTO $userDTO, string $id): void;
}