<?php

namespace App\Components\UserRegister\Business\Model;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserRegister\Persistence\DTO\UserRegisterDto;

interface RegisterInterface
{
    public function execute(UserRegisterDto $userRegisterDto): ?ErrorsDTO;
}