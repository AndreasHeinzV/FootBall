<?php

namespace App\Components\UserFavorite\Persistence;

use App\Components\Database\Persistence\Entity\FavoriteEntity;
use App\Components\User\Persistence\DTOs\UserDTO;

interface UserFavoriteRepositoryInterface
{
    public function getUserFavorites(UserDTO $userDTO): array;


    // public function getUserFavoritePositionByTeamId(UserDTO $userDTO, string $id): int|false;

    public function getUserFavoriteByTeamId(UserDTO $userDTO, int $teamId): ?FavoriteEntity;

    public function getUserFavoritesFirstPosition(UserDTO $userDTO): int|false;

    public function getUserFavoritesLastPosition(UserDTO $userDTO): int|false;

    public function getFavoritePositionAboveCurrentPosition(UserDTO $userDTO, int $position): int|false;

    public function getUserFavoriteEntityByPosition(UserDTO $userDTO, int $position): ?FavoriteEntity;

    public function getFavoritePositionBelowCurrentPosition(UserDTO $userDTO, int $position): int|false;
}