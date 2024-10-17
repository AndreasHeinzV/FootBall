<?php

namespace App\Components\User\Persistence\Mapper;

use App\Components\User\Persistence\DTOs\UserDTO;

interface UserMapperInterface
{

    public function createDTO(array $userData): UserDTO;

    public function UserDTOToArray(UserDTO $user): array;


}