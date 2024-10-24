<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordReset\Validation;

use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;

class ValidateFirstPassword
{

    public function validate(ResetErrorDTO $resetErrorDTO, ResetDTO $resetDTO): ResetErrorDTO
    {
        if (empty($resetDTO->FirstPassword)) {
            $resetErrorDTO->emptyFirstPW = "Password is empty";
            return $resetErrorDTO;
        }

        if (!preg_match('/^(?=.*\d)(?=.*[!?*#@$%^&])(?=.*[A-Z])(?=.*[a-z]).{7,}$/', $resetDTO->FirstPassword)) {

            $resetErrorDTO->firstPWValidationError = "Password must be at least 7 characters long and include at least one lowercase letter, 
        one uppercase letter, one number, and one special character like ?!*$#@%^&.";
            return $resetErrorDTO;
        }
        return $resetErrorDTO;
    }
}