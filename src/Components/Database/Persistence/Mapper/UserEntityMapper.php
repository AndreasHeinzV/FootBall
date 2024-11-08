<?php

declare(strict_types=1);

namespace App\Components\Database\Persistence\Mapper;

use App\Components\Database\Persistence\Entity\UserEntity;
use App\Components\User\Persistence\DTOs\UserDTO;

class UserEntityMapper
{

    public function mapUserDtoToUserEntity(UserDTO $userDTO): UserEntity
    {
        $userEntity = new UserEntity();
        $userEntity->setEmail($userDTO->email);
        $userEntity->setPassword($userDTO->password);
        $userEntity->setFirstName($userDTO->firstName);
        $userEntity->setLastName($userDTO->lastName);
        return $userEntity;
    }

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