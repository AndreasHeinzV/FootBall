<?php

namespace App\Components\User\Persistence;

use App\Components\User\Persistence\DTOs\UserDTO;

interface UserEntityManagerInterface
{
    public function saveUser(UserDTO $userDTO): void;
}