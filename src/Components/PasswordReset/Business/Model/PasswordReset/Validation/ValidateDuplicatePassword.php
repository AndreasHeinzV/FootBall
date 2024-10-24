<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordReset\Validation;

use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;

class ValidateDuplicatePassword
{

    public function validate(ResetErrorDTO $resetErrorDTO, ResetDTO $resetDTO): ResetErrorDTO
    {
        if ($resetDTO->FirstPassword !== $resetDTO->SecondPassword) {
            $resetErrorDTO->differentPWerror = "Passwords do not match";
        }
        return $resetErrorDTO;
    }
}