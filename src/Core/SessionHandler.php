<?php

declare(strict_types=1);

namespace App\Core;

use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapper;

class SessionHandler
{

    public UserDTO $emptyUser;

    public function __construct(private readonly UserMapper $mapper)
    {
    }


    public function startSession(): void
    {
        $_SESSION['status'] = true;
    }

    public function stopSession(): void
    {
        $_SESSION['status'] = false;
    }

    public function setUserDTO(UserDTO $userDTO): void
    {
        $_SESSION['userDto'] = $this->mapper->getUserData($userDTO);
    }


    public function getUserDTO(): UserDTO
    {
        if (isset($_SESSION['userDto'])) {
            $this->emptyUser = $this->mapper->createDTO($_SESSION['userDto']);
        } else {
            $this->emptyUser = new UserDTO('','', '', '', '');
        }
        return $this->emptyUser;
    }

    public function getStatus(): bool
    {
        return isset($_SESSION['status']) ? $_SESSION['status'] : false;
    }
}