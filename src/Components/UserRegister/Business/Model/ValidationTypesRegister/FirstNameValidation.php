<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Business\Model\ValidationTypesRegister;

class FirstNameValidation implements ValidationInterface
{

    public function validateInput(string $input): ?string
    {
        if (empty($input)) {
            return "Firstname is empty.";
        }
        return null;
    }
}