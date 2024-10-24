<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordFailed;

class EmailValidationPasswordFailed
{

    public function validate(string $email): bool
    {
        return !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    }

}