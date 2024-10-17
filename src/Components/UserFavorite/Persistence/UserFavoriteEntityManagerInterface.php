<?php

namespace App\Components\UserFavorite\Persistence;

use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserFavorite\Persistence\DTO\FavoriteDTO;

interface UserFavoriteEntityManagerInterface
{
    public function saveUserFavorite(UserDTO $userDTO, FavoriteDTO $favoriteDTO): void;

    public function updateUserFavoritePosition(
        int $userID,
        int $currentTeamID,
        int $prevTeamID,
        int $currentPosition,
        int $previousPosition
    ): void;

    public function deleteUserFavorite(UserDTO $userDTO, string $id): void;
}