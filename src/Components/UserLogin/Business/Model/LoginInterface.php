<?php

namespace App\Components\UserLogin\Business\Model;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;

interface LoginInterface
{
    public function execute(UserLoginDto $userLoginDto): ?ErrorsDTO;

}