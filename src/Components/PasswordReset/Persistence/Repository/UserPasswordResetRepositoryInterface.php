<?php

namespace App\Components\PasswordReset\Persistence\Repository;

use App\Components\PasswordReset\Persistence\DTOs\ActionDTO;

interface UserPasswordResetRepositoryInterface
{
    public function getUserIdFromActionId(string $actionId): int|false;

    public function getActionIdEntry(string $actionId): ActionDTO|false;
}