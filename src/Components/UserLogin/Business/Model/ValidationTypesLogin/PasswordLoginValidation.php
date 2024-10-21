<?php

declare(strict_types=1);

namespace App\Components\UserLogin\Business\Model\ValidationTypesLogin;

use App\Components\UserLogin\Persistence\DTO\UserLoginDto;

readonly class PasswordLoginValidation implements LoginValidationInterface
{
    public function __construct(private UserAuthentication $loginAuthentication)
    {
    }

    public function validateInput(UserLoginDto $userLoginDto): ?string
    {
        if (empty($userLoginDto->password)) {
            return "Password is empty.";
        }

        if (!$this->loginAuthentication->authenticateLogin($userLoginDto)) {
            return 'email or password is wrong';
        }
        return null;
    }
}