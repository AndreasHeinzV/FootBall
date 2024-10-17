<?php

declare(strict_types=1);

namespace App\Components\User\Persistence\Mapper;




use App\Components\User\Persistence\DTOs\UserDTO;

class UserMapper implements UserMapperInterface
{

    public function createDTO(array $userData): UserDTO
    {
        return new UserDTO(
            $userData['userId'] ?? null,
            $userData['firstName'] ?? '',
            $userData['lastName'] ?? '',
            $userData['email'],
            $userData['password']
        );
    }

    public function UserDTOToArray(UserDTO $user): array
    {
        return [
            'userId' => $user->userId,
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'email' => $user->email,
            'password' => $user->password,
        ];
    }
}