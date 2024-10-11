<?php

namespace App\Model;

use App\Model\DTOs\UserDTO;

interface UserRepositoryInterface
{
    public function getUserName(string $email): string;
    public function getUsers(): array;
    public function getUserFavorites(UserDTO $userDTO): array;
}