<?php

namespace App\Components\PasswordReset\Business;

use App\Components\PasswordReset\Persistence\DTOs\ActionDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;

interface PasswordResetBusinessFacadeInterface
{
    public function sendPasswordResetEmail(string $email): bool;

    public function resetUserPassword(string $actionId, ResetDTO $resetDTO): ResetErrorDTO|true;

    public function checkInputsForIntegrity(ActionDTO $actionDTO): bool;
}