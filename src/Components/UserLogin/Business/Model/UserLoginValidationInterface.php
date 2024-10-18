<?php

namespace App\Components\UserLogin\Business\Model;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;

interface UserLoginValidationInterface
{



    public function validateNoErrors(ErrorsDTO $errorsDTO);

    public function userLoginGetErrorsDTO(UserLoginDto $userLoginDto): ErrorsDTO;
}