<?php

declare(strict_types=1);

namespace App\Model\Mapper;




use App\Model\DTOs\UserDTO;

class UserMapper implements UserMapperInterface
{

    public function createDTO(array $userData): UserDTO
    {
        return new UserDTO(
            $userData['firstName'] ?? '',
            $userData['lastName'] ?? '',
            $userData['email'],
            $userData['password']
        );
    }

    public function getUserData(UserDTO $user): array
    {
        return [
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'email' => $user->email,
            'password' => $user->password,
        ];
    }
}