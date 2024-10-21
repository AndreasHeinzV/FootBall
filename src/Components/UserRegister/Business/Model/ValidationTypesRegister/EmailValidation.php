<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Business\Model\ValidationTypesRegister;

class EmailValidation implements ValidationInterface
{

    public function validateInput(string $input): ?string
    {
        if (empty($input)) {
            return "Email is empty.";
        }

        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email address.";
        }
        return null;
    }
}