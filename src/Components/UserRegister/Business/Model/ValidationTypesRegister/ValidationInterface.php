<?php

namespace App\Components\UserRegister\Business\Model\ValidationTypesRegister;

interface ValidationInterface
{

    public function validateInput(string $input): ?string;
}