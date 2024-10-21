<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Business\Model\ValidationTypesRegister;

class PasswordValidation implements ValidationInterface
{

    public function validateInput(string $input): ?string
    {
        if (empty($input)) {
            return "Password is empty.";
        }

        if (!preg_match('/^(?=.*\d)(?=.*[!?*#@$%^&])(?=.*[A-Z])(?=.*[a-z]).{7,}$/', $input)) {
            return "Password must be at least 7 characters long and include at least one lowercase letter, 
        one uppercase letter, one number, and one special character like ?!*$#@%^&.";
        }
        return null;
    }
}