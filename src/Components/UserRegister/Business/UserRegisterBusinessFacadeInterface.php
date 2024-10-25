<?php

namespace App\Components\UserRegister\Business;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserRegister\Persistence\DTO\UserRegisterDto;

interface UserRegisterBusinessFacadeInterface
{
   // public function registerUser(UserDTO $userDTO): void;
    public function registerUserNew(UserRegisterDto $userRegisterDto): ?ErrorsDTO;
}