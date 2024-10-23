<?php

declare(strict_types=1);

namespace App\Components\UserLogin\Business\Model\ValidationTypesLogin;

use App\Components\UserLogin\Persistence\DTO\UserLoginDto;

class EmailLoginValidation implements LoginValidationInterface
{
    public function validateInput(UserLoginDto $userLoginDto): ?string{

        if (empty($userLoginDto->email)) {
            return "Email is empty.";
        }

        if (!filter_var($userLoginDto->email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        }
        return null;
    }
}