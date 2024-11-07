<?php

namespace App\Components\UserRegister\Business\Model;

use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\UserRegister\Persistence\DTO\UserRegisterDto;

interface UserRegisterValidationInterface
{

    public function validateNoErrors(ErrorsDTO $errorsDTO): bool;

    public function userRegisterGetErrorsDTO(UserRegisterDto $userRegisterDto): ErrorsDTO;

}