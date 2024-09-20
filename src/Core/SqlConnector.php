<?php

declare(strict_types=1);

namespace App\Core;

use App\Model\DTOs\UserDTO;

class SqlConnector
{


    public function getUserData(UserDto $userDto): array{
        return [];
    }
    public function setUserData(UserDto $userDto): void
    {

    }
    public function updateUserData(UserDto $userDto, int $position): void{

    }


}