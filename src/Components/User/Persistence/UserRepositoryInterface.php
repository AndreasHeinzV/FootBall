<?php

namespace App\Components\User\Persistence;

use App\Components\User\Persistence\DTOs\UserDTO;

interface UserRepositoryInterface
{
    public function getUsers(): array;

    public function getUser(string $email);

    public function getUserID(UserDTO $userDTO);
}