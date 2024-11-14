<?php

namespace App\Components\UserFavorite\Persistence;

use App\Components\Database\Persistence\Entity\FavoriteEntity;
use App\Components\User\Persistence\DTOs\UserDTO;

interface UserFavoriteRepositoryInterface
{
    public function getUserFavorites(UserDTO $userDTO): array;

    public function checkExistingFavorite(UserDTO $userDTO, string $teamID): bool;

    public function getUserFavoritePositionByTeamId(UserDTO $userDTO, string $id): int|false;

    public function getUserMinFavoritePosition(UserDTO $userDTO): int|false;

    public function getUserFavoritesFirstPosition(UserDTO $userDTO): int|false;

    public function getUserFavoritesLastPosition(UserDTO $userDTO): int|false;

    public function getFavoritePositionAboveCurrentPosition(UserDTO $userDTO, int $position): int|false;

    public function getUserFavoriteEntityByPosition(UserDTO $userDTO, int $maxPosition): ?FavoriteEntity;

    public function getFavoritePositionBelowCurrentPosition(UserDTO $userDTO, int $position): int|false;
}