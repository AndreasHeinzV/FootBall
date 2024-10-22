<?php

namespace App\Components\User\Business;

use App\Components\User\Persistence\DTOs\UserDTO;

interface UserBusinessFacadeInterface
{
    public function getUserByMail(string $email): UserDTO;

    public function getUserIdByMail(UserDTO $userDTO): int|false;

    public function getUsers(): array;

    public function registerUser(UserDTO $userDTO): void;
}