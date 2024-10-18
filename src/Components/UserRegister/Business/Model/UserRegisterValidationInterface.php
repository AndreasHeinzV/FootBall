<?php

namespace App\Components\UserRegister\Business\Model;

use App\Components\User\Persistence\DTOs\ErrorsDTO;

interface UserRegisterValidationInterface
{

    public function validateNoErrors(ErrorsDTO $errorsDTO): bool;


}