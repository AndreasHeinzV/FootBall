<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Business\Model\ValidationTypesRegister;

class LastNameValidation implements ValidationInterface
{


    public function validateInput(string $input): ?string
    {
        if (empty($input)) {
            return "Lastname is empty.";
        }
        return null;
    }
}