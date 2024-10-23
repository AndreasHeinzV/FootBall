<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business;

use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailCoordinator;
use App\Components\PasswordReset\Business\Model\PasswordReset\ResetCoordinator;
use App\Components\PasswordReset\Persistence\DTOs\MailDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;

readonly class PasswordResetBusinessFacade implements PasswordResetBusinessFacadeInterface
{
    public function __construct(private EmailCoordinator $emailCoordinator, private ResetCoordinator $resetCoordinator)
    {
    }

    public function sendPasswordResetEmail(string $email): bool
    {
        return $this->emailCoordinator->coordinateEmailTransfer($email);
    }

    public function resetUserPassword(MailDTO $mailDTO): ResetErrorDTO|true
    {
       return $this->resetCoordinator->coordinateResetPassword($mailDTO);
    }
    public function checkInputsForIntegrity(MailDTO $mailDTO): bool
    {
        //todo add integrity check
        return true;
    }
}