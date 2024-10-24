<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordReset\Validation;

use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;

class ValidateSecondPassword
{

    public function validate(ResetErrorDTO $resetErrorDTO, ResetDTO $resetDTO): ResetErrorDTO
    {
        if (empty($resetDTO->SecondPassword)) {
            $resetErrorDTO->emptySecondPW = "Password is empty";
            return $resetErrorDTO;
        }

        if (!preg_match('/^(?=.*\d)(?=.*[!?*#@$%^&])(?=.*[A-Z])(?=.*[a-z]).{7,}$/', $resetDTO->SecondPassword)) {

            $resetErrorDTO->secondPWValidationError = "Password must be at least 7 characters long and include at least one lowercase letter, 
        one uppercase letter, one number, and one special character like ?!*$#@%^&.";
            return $resetErrorDTO;
        }
        return $resetErrorDTO;
    }
}