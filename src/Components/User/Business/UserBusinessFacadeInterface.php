<?php

namespace App\Components\User\Business;

use App\Components\User\Persistence\DTOs\UserDTO;

interface UserBusinessFacadeInterface
{
    public function getUserByMail(string $email): UserDTO;

    public function getUserById(UserDTO $userDTO): int;

    public function getUsers(): array;

    public function registerUser(UserDTO $userDTO): void;
}