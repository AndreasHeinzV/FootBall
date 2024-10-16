<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Business;

use App\Components\UserFavorite\Persistence\UserFavoriteEntityManager;
use App\Components\UserFavorite\Persistence\UserFavoriteRepository;
use App\Core\SessionHandler;
use App\Model\DTOs\CompetitionDTO;
use App\Model\DTOs\FavoriteDTO;
use App\Model\DTOs\TeamDTO;
use App\Model\DTOs\UserDTO;
use App\Model\FootballRepositoryInterface;
use App\Model\Mapper\FavoriteMapper;
use App\Model\UserEntityManager;
use App\Model\UserRepository;
use SessionHandlerInterface;


class UserFavoriteHandler
{
    public array $favoritesList = [];

    public UserDTO $userDTO;

    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly FootballRepositoryInterface $footballRepository,
        private readonly UserFavoriteEntityManager $userFavoriteEntityManager,
        private readonly UserFavoriteRepository $userFavoriteRepository,
        private readonly FavoriteMapper $favoriteMapper
    ) {
    }

    public function addUserFavorite(UserDTO $userDTO, string $id): void
    {
        $team = $this->footballRepository->getTeam($id);
        if (!empty($team) && !$this->getFavStatus($id)) {
            $this->userFavoriteEntityManager->saveUserFavorite($userDTO, $this->favoriteMapper->createFavoriteDTO($team));
        }
    }


    public function removeUserFavorite(UserDTO $userDTO, string $id): void
    {

      $this->userFavoriteEntityManager->deleteUserFavorite($userDTO, $id);
    }

    public function getUserFavorites(UserDTO $userDTO): array
    {
        return $this->userFavoriteRepository->getUserFavorites($userDTO);
    }

    public function getFavStatus(string $id): bool
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        return $this->userFavoriteRepository->checkExistingFavorite($userDTO, $id);
    }


    public function moveUserFavorite(string $id, string $movement): void
    {
        $this->userDTO = $this->sessionHandler->getUserDTO();
        if ($movement === "up") {
            $this->moveFavUp($this->userDTO, $id);
        }
        if ($movement === "down") {
            $this->moveFavDown($this->userDTO, $id);
        }
    }

    private function moveFavUp(UserDTO $userDTO, string $id): void
    {

        $minPosition = $this->userFavoriteRepository->getUserMinFavoritePosition($this->userDTO);
        $position = $this->userFavoriteRepository->getUserFavoritePosition($this->userDTO, $id);

        if ($position > $minPosition) {
            $favoritesArray = $this->userFavoriteRepository->getUserFavorites($this->userDTO);


            foreach ($favoritesArray as $i => $favorite) {
                if ($favorite['favoritePosition'] === $position) {
                    $currentIndex = $i;
                    $prevIndex = $currentIndex - 1;

                    $currentFavoriteTeamID = $favorite['teamID'];
                    $prevFavTeamIndex = $favoritesArray[$prevIndex];
                    $prevFavTeamID = $prevFavTeamIndex['teamID'];

                    $this->userFavoriteEntityManager->updateUserFavoritePosition(
                        (int)$userDTO->userId,
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

    private function moveFavDown(UserDTO $userDTO, string $id): void
    {

        $maxPosition = $this->userFavoriteRepository->getUserMaxFavoritePosition($this->userDTO);
        $position = $this->userFavoriteRepository->getUserFavoritePosition($this->userDTO, $id);

        if ($position < $maxPosition) {
            $favoritesArray = $this->userFavoriteRepository->getUserFavorites($this->userDTO);

            foreach ($favoritesArray as $i => $favorite) {
                if ($favorite['favoritePosition'] === $position) {
                    $currentIndex = $i;
                    $nextIndex = $currentIndex + 1;
                    if (isset($favoritesArray[$nextIndex])) {
                        $currentFavoriteTeamID = $favorite['teamID'];
                        $nextFavTeamIndex = $favoritesArray[$nextIndex];
                        $nextFavTeamID = $nextFavTeamIndex['teamID'];

                        $this->userFavoriteEntityManager->updateUserFavoritePosition(
                            (int)$userDTO->userId,
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