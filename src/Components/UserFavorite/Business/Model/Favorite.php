<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Business\Model;

use App\Components\Football\Business\Model\FootballBusinessFacade;
use App\Components\Football\Business\Model\FootballBusinessFacadeInterface;
use App\Components\UserFavorite\Persistence\Mapper\FavoriteMapper;
use App\Components\UserFavorite\Persistence\Mapper\FavoriteMapperInterface;
use App\Components\UserFavorite\Persistence\UserFavoriteEntityManager;
use App\Components\UserFavorite\Persistence\UserFavoriteEntityManagerInterface;
use App\Components\UserFavorite\Persistence\UserFavoriteRepository;
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
                    $this->userFavoriteUp($value);
                    break;

                case ($keyValue === 'down'):
                    $this->userFavoriteDown($value);
                    break;
            }
        }
    }

    public function handleRemove(string $teamId): void
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        $this->userFavoriteEntityManager->deleteUserFavorite($userDTO, $teamId);
    }

    public function handleAdd( string $teamId): void
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        $team = $this->footballBusinessFacade->getTeam($teamId);

        if (!empty($team) && !$this->getFavStatus($teamId)) {
            $this->userFavoriteEntityManager->saveUserFavorite(
                $userDTO,
                $this->favoriteMapper->createFavoriteDTO($team)
            );
        }
    }

    public function getFavStatus(string $teamId): bool
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        return $this->userFavoriteRepository->checkExistingFavorite($userDTO, $teamId);
    }

    private function userFavoriteUp(string $teamId): void
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        $minPosition = $this->userFavoriteRepository->getUserMinFavoritePosition($userDTO);
        $position = $this->userFavoriteRepository->getUserFavoritePosition($userDTO, $teamId);

        if ($position > $minPosition) {
            $favoritesArray = $this->userFavoriteRepository->getUserFavorites($userDTO);


            foreach ($favoritesArray as $i => $favorite) {
                if ($favorite['favoritePosition'] === $position) {
                    $currentIndex = $i;
                    $prevIndex = $currentIndex - 1;

                    $currentFavoriteTeamID = $favorite['teamID'];
                    $prevFavTeamIndex = $favoritesArray[$prevIndex];
                    $prevFavTeamID = $prevFavTeamIndex['teamID'];

                    $this->userFavoriteEntityManager->updateUserFavoritePosition(
                        $userDTO->userId,
                        $currentFavoriteTeamID,
                        $prevFavTeamID,
                        $position,
                        $prevFavTeamIndex['favoritePosition']
                    );
                    break;
                }
            }
        }
    }

    private function userFavoriteDown(string $id): void
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        $maxPosition = $this->userFavoriteRepository->getUserMaxFavoritePosition($userDTO);
        $position = $this->userFavoriteRepository->getUserFavoritePosition($userDTO, $id);

        if ($position < $maxPosition) {
            $favoritesArray = $this->userFavoriteRepository->getUserFavorites($userDTO);

            foreach ($favoritesArray as $i => $favorite) {
                if ($favorite['favoritePosition'] === $position) {
                    $currentIndex = $i;
                    $nextIndex = $currentIndex + 1;
                    if (isset($favoritesArray[$nextIndex])) {
                        $currentFavoriteTeamID = $favorite['teamID'];
                        $nextFavTeamIndex = $favoritesArray[$nextIndex];
                        $nextFavTeamID = $nextFavTeamIndex['teamID'];

                        $this->userFavoriteEntityManager->updateUserFavoritePosition(
                            $userDTO->userId,
                            $currentFavoriteTeamID,
                            $nextFavTeamID,
                            $position,
                            $nextFavTeamIndex['favoritePosition']
                        );
                    }
                    break;
                }
            }
        }
    }
}