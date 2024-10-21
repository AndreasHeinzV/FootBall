<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Persistence\Mapper;

use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserRegister\Persistence\DTO\UserRegisterDto;

class RegisterMapper
{
    public function mapRegisterDtoToUserDTO(UserRegisterDto $userRegisterDto): UserDTO
    {
        return new UserDTO(
            null,
            $userRegisterDto->firstName,
            $userRegisterDto->lastName,
            $userRegisterDto->email,
            $userRegisterDto->password
        );
    }
}