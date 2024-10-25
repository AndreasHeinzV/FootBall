<?php

namespace App\Components\PasswordReset\Persistence\Repository;

interface UserPasswordResetRepositoryInterface
{
    public function getUserIdFromActionId(string $actionId): int|false;

    public function getActionIdEntry(string $actionId): array|false;
}