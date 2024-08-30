<?php

namespace App\Core;

use App\Model\DTOs\ErrorsDTO;
use App\Model\DTOs\UserDTO;

interface ValidationInterface
{

    public function checkDuplicateMail(array $existingUsers, string $mailToCheck): bool;

    public function userRegisterValidation(UserDTO $userDTO): ErrorsDTO;

    public function validateErrors(ErrorsDTO $errorsDTO);
}