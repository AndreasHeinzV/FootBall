<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business;

use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailCoordinator;
use App\Components\PasswordReset\Business\Model\PasswordReset\AccessManager;
use App\Components\PasswordReset\Business\Model\PasswordReset\ResetCoordinator;
use App\Components\PasswordReset\Persistence\DTOs\ActionDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;

readonly class PasswordResetBusinessFacade implements PasswordResetBusinessFacadeInterface
{
    public function __construct(private EmailCoordinator $emailCoordinator, private ResetCoordinator $resetCoordinator,private AccessManager $accessManager)
    {
    }

    public function sendPasswordResetEmail(string $email): bool
    {
        return $this->emailCoordinator->coordinateEmailTransfer($email);
    }

    public function resetUserPassword(string $actionId, ResetDTO $resetDTO): ResetErrorDTO|true
    {
        return $this->resetCoordinator->coordinateResetPassword($actionId, $resetDTO);
    }

    public function checkInputsForIntegrity(ActionDTO $actionDTO): bool
    {
        return $this->accessManager->checkForAccess($actionDTO);
    }
}