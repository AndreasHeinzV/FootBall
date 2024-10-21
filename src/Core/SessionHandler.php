<?php

declare(strict_types=1);

namespace App\Core;

use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\Mapper\UserMapperInterface;

 class SessionHandler implements SessionHandlerInterface
{

    public UserDTO $userDTO;

    public function __construct(private readonly UserMapperInterface $userMapper)
    {
    }


    public function startSession(UserDTO $userDTO): void
    {
        $_SESSION['status'] = true;
        $_SESSION['userDto'] = $this->userMapper->UserDTOToArray($userDTO);

    }

    public function stopSession(): void
    {
        $_SESSION['status'] = false;
    }


    public function getUserDTO(): UserDTO
    {
        if (isset($_SESSION['userDto'])) {
            $this->userDTO = $this->userMapper->createDTO($_SESSION['userDto']);
        } else {
            $this->userDTO = new UserDTO(null,'', '', '', '');
        }
        return $this->userDTO;
    }

    public function getStatus(): bool
    {
        return isset($_SESSION['status']) ? $_SESSION['status'] : false;
    }
}