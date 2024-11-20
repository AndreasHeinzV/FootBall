<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Persistence\Repository;

use App\Components\Database\Persistence\Entity\ResetPasswordEntity;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SqlConnectorInterface;
use App\Components\PasswordReset\Persistence\DTOs\ActionDTO;
use App\Components\PasswordReset\Persistence\Mapper\ActionMapper;
use Doctrine\ORM\EntityManager;

readonly class UserPasswordResetRepository implements UserPasswordResetRepositoryInterface
{
    private EntityManager $entityManager;

    public function __construct(private ORMSqlConnector $sqlConnector, private ActionMapper $actionMapper)
    {
        $this->entityManager = $this->sqlConnector->getEntityManager();
    }


    public function getUserIdFromActionId(string $actionId): int|false
    {

        $entity = $this->entityManager->getRepository(ResetPasswordEntity::class)->findOneBy(['actionId' => $actionId]);
        if ($entity instanceof ResetPasswordEntity) {
            return $entity->getUserId();
        }
        return false;
    }

    public function getActionIdEntry(string $actionId): ActionDTO|false
    {

        $entity = $this->entityManager->getRepository(ResetPasswordEntity::class)->findOneBy(['actionId' => $actionId]);
        if ($entity instanceof ResetPasswordEntity) {
            return $this->actionMapper->mapEntityToActionDto($entity);
        }
        return false;
    }

}