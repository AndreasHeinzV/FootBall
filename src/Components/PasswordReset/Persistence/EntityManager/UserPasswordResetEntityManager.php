<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\EntityManager;

use App\Components\Database\Persistence\Entity\ResetPasswordEntity;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\PasswordReset\Persistence\DTOs\MailDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

readonly class UserPasswordResetEntityManager
{
    private EntityManager $entityManager;

    public function __construct(private ORMSqlConnector $sqlConnector)
    {
        $this->entityManager = $this->sqlConnector->getEntityManager();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function savePasswordResetAction(UserDTO $userDTO, MailDTO $mailDTO): void
    {
        $passwordResetEntity = new ResetPasswordEntity();
        $passwordResetEntity->setActionId($mailDTO->actionId);
        $passwordResetEntity->setUserId($userDTO->userId);
        $passwordResetEntity->setTimestamp($mailDTO->timestamp);


        $this->entityManager->persist($passwordResetEntity);
        $this->entityManager->flush();
    }

    public function deletePasswordResetAction(string $actionId): void
    {
        $entity = $this->entityManager->find(ResetPasswordEntity::class, $actionId);
        if ($entity !== null) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        }
    }
}