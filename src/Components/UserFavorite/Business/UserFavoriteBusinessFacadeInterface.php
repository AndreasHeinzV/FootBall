<?php

namespace App\Components\UserFavorite\Business;

use App\Components\User\Persistence\DTOs\UserDTO;


interface UserFavoriteBusinessFacadeInterface
{
    public function manageFavoriteInput(array $input): void;

    public function getFavoriteStatus(string $teamId): bool;

    public function getUserFavorites(UserDTO $userDTO): array;
}