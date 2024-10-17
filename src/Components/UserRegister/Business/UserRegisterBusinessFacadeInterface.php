<?php

namespace App\Components\UserRegister\Business;

use App\Components\User\Persistence\DTOs\UserDTO;

interface UserRegisterBusinessFacadeInterface
{
    public function registerUser(UserDTO $userDTO): void;
}