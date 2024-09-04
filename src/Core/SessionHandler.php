<?php

declare(strict_types=1);

namespace App\Core;

use App\Model\DTOs\UserDTO;

class SessionHandler
{
    private bool $status;
    public UserDTO $user;

    public function __construct()
    {
        $this->user = new UserDTO('','','','');
        $this->status = false;
    }


    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }

    public function setUserDTO(UserDTO $userDTO): void
    {
        $this->user = $userDTO;
    }


    public function getUserDTO(): UserDTO
    {
        return $this->user;
    }
    public function getStatus(): bool{
        return $this->status;
    }
}