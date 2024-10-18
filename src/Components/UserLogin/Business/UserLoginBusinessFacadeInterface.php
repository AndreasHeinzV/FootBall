<?php

namespace App\Components\UserLogin\Business;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;

interface UserLoginBusinessFacadeInterface
{
    public function loginUser(UserLoginDto $userLoginDto): ?ErrorsDTO;
}