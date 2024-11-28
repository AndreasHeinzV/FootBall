<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Business\Model;

use App\Components\Database\Persistence\Entity\FavoriteEntity;
use App\Components\Football\Business\Model\FootballBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\UserFavorite\Persistence\Mapper\FavoriteMapperInterface;
use App\Components\UserFavorite\Persistence\UserFavoriteEntityManagerInterface;
use App\Components\UserFavorite\Persistence\UserFavoriteRepositoryInterface;
use App\Core\SessionHandler;

readonly class Favorite implements FavoriteInterface
{


    public function __construct(
        private SessionHandler $sessionHandler,
        private FootballBusinessFacadeInterface $footballBusinessFacade,
        private UserFavoriteEntityManagerInterface $userFavoriteEntityManager,
        private UserFavoriteRepositoryInterface $userFavoriteRepository,
        private FavoriteMapperInterface $favoriteMapper
    ) {
    }

    /**
     * @@param array{add?: string, delete?: string, up?: string, down?: string} $input
     * @return void
     */
    public function manageFav(array $input): void
    {
        foreach ($input as $keyValue => $value) {
            switch (true) {
                case ($keyValue === 'add'):
                    $this->handleAdd($value);
                    break;
                case ($keyValue === 'delete'):
                    $this->handleRemove($value);
                    break;

                case ($keyValue === 'up'):
                    $this->userFavoriteUp((int)$value);
                    break;

                case ($keyValue === 'down'):
                    $this->userFavoriteDown((int)$value);
                    break;
            }
        }
    }

    public function handleRemove(string $teamId): void
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        $this->userFavoriteEntityManager->deleteUserFavorite($userDTO, $teamId);
    }

    public function handleAdd(string $teamId): void
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        $team = $this->footballBusinessFacade->getTeam($teamId);
        $position = $this->calculatePosition($userDTO);

        if (!empty($team) && !$this->getFavStatus($teamId)) {
            $team['favoritePosition'] = $position;
            $this->userFavoriteEntityManager->saveUserFavorite(
                $userDTO,
                $this->favoriteMapper->createFavoriteDTO($team)
            );
        }
    }

    public function calculatePosition(UserDTO $userDTO): int
    {
        $lastPosition = $this->userFavoriteRepository->getUserFavoritesLastPosition($userDTO);
        if ($lastPosition === false) {
            return 1;
        }
        return $lastPosition + 1;
    }

    public function getFavStatus(string $teamId): bool
    {
        $userDTO = $this->sessionHandler->getUserDTO();

        $favoriteEntity = $this->userFavoriteRepository->getUserFavoriteByTeamId($userDTO, (int)$teamId);
        return $favoriteEntity instanceof FavoriteEntity;
    }

    private function userFavoriteUp(int $teamId): void
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        $userFavoriteEntity = $this->userFavoriteRepository->getUserFavoriteByTeamId($userDTO, $teamId);


        if ($userFavoriteEntity instanceof FavoriteEntity) {
            $favoritePosition = $userFavoriteEntity->getFavoritePosition();
            $firstPosition = $this->userFavoriteRepository->getUserFavoritesFirstPosition($userDTO);

            if ($firstPosition !== false && $firstPosition < $favoritePosition) {
                $positionToChange = $this->userFavoriteRepository->getFavoritePositionAboveCurrentPosition(
                    $userDTO,
                    $favoritePosition
                );
                $positionEntityToChange = $this->userFavoriteRepository->getUserFavoriteEntityByPosition(
                    $userDTO,
                    $positionToChange
                );


                $this->userFavoriteEntityManager->updateUserFavoritePosition(
                    $userFavoriteEntity,
                    $positionEntityToChange,
                    $favoritePosition,
                    $positionToChange
                );
            }
        }
    }

    private function userFavoriteDown(int $teamId): void
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        $userFavoriteEntity = $this->userFavoriteRepository->getUserFavoriteByTeamId($userDTO, $teamId);


        if ($userFavoriteEntity instanceof FavoriteEntity) {
            $favoritePosition = $userFavoriteEntity->getFavoritePosition();
            $lastPosition = $this->userFavoriteRepository->getUserFavoritesLastPosition($userDTO);

            if ($lastPosition !== false && $lastPosition > $favoritePosition) {
                $positionToChange = $this->userFavoriteRepository->getFavoritePositionBelowCurrentPosition(
                    $userDTO,
                    $favoritePosition
                );
                $positionEntityToChange = $this->userFavoriteRepository->getUserFavoriteEntityByPosition(
                    $userDTO,
                    $positionToChange
                );


                $this->userFavoriteEntityManager->updateUserFavoritePosition(
                    $userFavoriteEntity,
                    $positionEntityToChange,
                    $favoritePosition,
                    $positionToChange
                );
            }
        }
    }
}