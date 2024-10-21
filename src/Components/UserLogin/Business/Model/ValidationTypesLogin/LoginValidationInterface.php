<?php

namespace App\Components\UserLogin\Business\Model\ValidationTypesLogin;

use App\Components\UserLogin\Persistence\DTO\UserLoginDto;

interface LoginValidationInterface
{
    public function validateInput(UserLoginDto $userLoginDto): ?string;
}