<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordReset;

use App\Components\PasswordReset\Persistence\DTOs\ActionDTO;
use App\Components\PasswordReset\Persistence\Mapper\ActionMapper;
use App\Components\PasswordReset\Persistence\Repository\UserPasswordResetRepositoryInterface;

readonly class AccessManager
{
    public function __construct(
        private UserPasswordResetRepositoryInterface $repository,
        private ActionMapper $actionMapper,
        private TimeManager $timeManager,
    ) {
    }

    public function checkForAccess(ActionDTO $actionDTO): bool
    {
        $actionEntry = $this->repository->getActionIdEntry($actionDTO->actionId);
        if (!$actionEntry) {
            return false;
        }
        $actionDTO = $this->actionMapper->mapArrayToActionDto($actionEntry);

        if ($actionDTO->timestamp === null) {
            return false;
        }
        return $this->timeManager->compareTimestamp($actionDTO->timestamp);
    }
}