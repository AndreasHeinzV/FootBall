<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordFailed;

use App\Components\PasswordReset\Business\Model\PasswordReset\TimeManager;
use App\Components\PasswordReset\Persistence\DTOs\MailDTO;
use App\Components\PasswordReset\Persistence\EntityManager\UserPasswordResetEntityManager;
use App\Components\User\Business\UserBusinessFacadeInterface;


readonly class EmailCoordinator
{

    public function __construct(
        private EmailBuilder $emailBuilder,
        private EmailDispatcherInterface $emailDispatcher,
        private EmailValidationPasswordFailed $emailValidation,
        private TimeManager $timeManager,
        private ActionIdGenerator $actionIdGenerator,
        private UserPasswordResetEntityManager $userPasswordResetEntityManager,
        private UserBusinessFacadeInterface $userBusinessFacade
    ) {
    }

    public function coordinateEmailTransfer(string $emailAddress): bool
    {
        if (!$this->emailValidation->validate($emailAddress)) {
            return false;
        }

        $userDTO = $this->userBusinessFacade->getUserByMail($emailAddress);
        if ($userDTO->userId === null) {
            return false;
        }

        $mailDTO = new MailDTO();
        $mailDTO->email = $emailAddress;
        $mailDTO = $this->timeManager->setTimestamp($mailDTO);
        $mailDTO = $this->actionIdGenerator->generate($mailDTO);
        $mailDTO = $this->emailBuilder->buildMail($mailDTO);


        $this->userPasswordResetEntityManager->savePasswordResetAction($userDTO, $mailDTO);

        return $this->emailDispatcher->sendMail($mailDTO);
    }
}