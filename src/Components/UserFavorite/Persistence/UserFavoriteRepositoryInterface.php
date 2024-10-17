<?php

namespace App\Components\UserFavorite\Persistence;

use App\Components\User\Persistence\DTOs\UserDTO;

interface UserFavoriteRepositoryInterface
{
    public function getUserFavorites(UserDTO $userDTO): array;

    public function checkExistingFavorite(UserDTO $userDTO, string $teamID): bool;

    public function getUserFavoritePosition(UserDTO $userDTO, string $id): int|false;

    public function getUserMinFavoritePosition(UserDTO $userDTO): int|false;

    public function getUserMaxFavoritePosition(UserDTO $userDTO): int|false;
}