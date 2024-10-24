<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordReset;

use App\Components\PasswordReset\Persistence\DTOs\ResetDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;
use App\Components\PasswordReset\Persistence\EntityManager\UserPasswordResetEntityManager;
use App\Components\PasswordReset\Persistence\Repository\UserPasswordResetRepository;
use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\Mapper\UserMapper;

readonly class ResetCoordinator
{


    public function __construct(
        private ResetErrorDtoProvider $getResetErrors,
        private UserPasswordResetRepository $userPasswordResetRepository,
        private UserPasswordResetEntityManager $userPasswordResetEntityManager,
        private UserBusinessFacadeInterface $userBusinessFacade,
        private UserMapper $userMapper,
    ) {
    }


    public function coordinateResetPassword(string $actionId, ResetDTO $resetDTO): ResetErrorDTO|true
    {
        $getResetErrors = $this->getResetErrors->getResetErrors($resetDTO);
        if ($getResetErrors instanceof ResetErrorDTO) {
            return $getResetErrors;
        }

        $userId = $this->userPasswordResetRepository->getUserIdFromActionId($actionId);
        $this->userBusinessFacade->updateUserPassword($this->userMapper->UserDTOWithOnlyUserId($userId));
        $this->userPasswordResetEntityManager->deletePasswordResetAction($actionId);
        return true;
    }
}