<?php

namespace App\Components\Validation;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;

interface ValidationInterface
{


    public function userRegisterGetErrors(UserDTO $userDTO): ErrorsDTO;

    public function validateNoErrors(ErrorsDTO $errorsDTO);

    public function userLoginGetErrorsDTO(UserDTO $userDTO): ErrorsDTO;
}