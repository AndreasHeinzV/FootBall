<?php

declare(strict_types=1);

namespace App\Components\Database\Persistence\Mapper;

use App\Components\Database\Persistence\Entity\UserEntity;
use App\Components\User\Persistence\DTOs\UserDTO;

class UserEntityMapper
{

    public function mapUserEntityToUserDto(UserEntity $userEntity): UserDTO
    {
        return new UserDTO(
            $userEntity->getId(),
            $userEntity->getFirstName(),
            $userEntity->getLastName(),
            $userEntity->getEmail(),
            $userEntity->getPassword()
        );
    }

}