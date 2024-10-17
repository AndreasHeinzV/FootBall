<?php

namespace App\Core;

use App\Components\User\Persistence\DTOs\UserDTO;

interface SessionHandlerInterface
{
    public function startSession(UserDTO $userDTO): void;

    public function stopSession(): void;

    public function getUserDTO(): UserDTO;

    public function getStatus(): bool;
}