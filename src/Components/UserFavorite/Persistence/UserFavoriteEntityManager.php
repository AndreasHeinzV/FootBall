<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Persistence;

use App\Components\Database\Persistence\Entity\FavoriteEntity;
use App\Components\Database\Persistence\Entity\UserEntity;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SqlConnectorInterface;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserFavorite\Business\Model\Favorite;
use App\Components\UserFavorite\Persistence\DTO\FavoriteDTO;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

readonly class UserFavoriteEntityManager implements UserFavoriteEntityManagerInterface
{
    private EntityManager $entityManager;

    public function __construct(
        private ORMSqlConnector $sqlConnector
    ) {
        $this->entityManager = $sqlConnector->getEntityManager();
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function saveUserFavorite(UserEntity $userEntity, FavoriteDTO $favoriteDTO): void
    {
        $favorite = new FavoriteEntity();
        $favorite->setFavoritePosition($favoriteDTO->position);
        $favorite->setTeamCrest($favoriteDTO->crest);
        $favorite->setTeamName($favoriteDTO->teamName);
        $favorite->setTeamId($favoriteDTO->teamID);
        $favorite->setUser($userEntity);

        $this->entityManager->persist($favorite);
        $this->entityManager->flush();
    }

    public function updateUserFavoritePosition(
        int $userID,
        int $currentTeamID,
        int $prevTeamID,
        int $currentPosition,
        int $previousPosition
    ): void {
        $this->sqlConnector->queryManipulate(
            'UPDATE favorites SET favorite_position = -1 WHERE user_id = :user_id AND team_id = :team_id',
            [
                'user_id' => $userID,
                'team_id' => $currentTeamID,
            ]
        );

        $this->sqlConnector->queryManipulate(
            'UPDATE favorites SET favorite_position =:favorite_position WHERE user_id = :user_id AND team_id = :team_id ',
            [
                'favorite_position' => $currentPosition,
                'user_id' => $userID,
                'team_id' => $prevTeamID,
            ]
        );

        $this->sqlConnector->queryManipulate(
            'UPDATE favorites SET favorite_position =:favorite_position WHERE user_id = :user_id AND team_id = :team_id ',
            [
                'favorite_position' => $previousPosition,
                'user_id' => $userID,
                'team_id' => $currentTeamID,
            ]
        );
    }

    public function deleteUserFavorite(UserDTO $userDTO, string $id): void
    {
        $this->sqlConnector->queryManipulate(
            '
       DELETE FROM favorites where team_id = :team_id and user_id = :user_id',
            [
                'team_id' => (int)$id,
                'user_id' => $userDTO->userId,
            ]
        );
/*
        $this->entityManager->getRepository()
       $this->entityManager->remove($userDTO);
*/
    }
}