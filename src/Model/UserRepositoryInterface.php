<?php

namespace App\Model;

use App\Model\DTOs\UserDTO;

interface UserRepositoryInterface
{
    public function getUserName(array $existingUsers, string $email): string;

    public function getUsers(): array;

    public function getFilePath(): string;

    public function getFavorites(): array;

    public function getUserFavorites(UserDTO $userDTO): array;
    public function getFavFilePath(): string;
}