<?php

namespace App\Components\PasswordReset\Business;

use App\Components\PasswordReset\Persistence\DTOs\MailDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;

interface PasswordResetBusinessFacadeInterface
{
    public function sendPasswordResetEmail(string $email): bool;

    public function resetUserPassword(MailDTO $mailDTO): ResetErrorDTO|true;

    public function checkInputsForIntegrity(MailDTO $mailDTO): bool;
}