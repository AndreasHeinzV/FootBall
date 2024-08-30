<?php

namespace App\Model\Mapper;

use App\Model\DTOs\UserDTO;

interface UserMapperInterface
{

    public function createDTO(array $userData): UserDTO;

    public function getUserData(UserDTO $user): array;


}