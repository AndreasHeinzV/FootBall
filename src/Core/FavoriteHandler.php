<?php

declare(strict_types=1);

namespace App\Core;

use App\Model\DTOs\CompetitionDTO;
use App\Model\DTOs\FavoriteDTO;
use App\Model\DTOs\TeamDTO;
use App\Model\DTOs\UserDTO;
use App\Model\FootballRepositoryInterface;
use App\Model\Mapper\FavoriteMapper;
use App\Model\UserEntityManager;
use App\Model\UserRepository;
use SessionHandlerInterface;


class FavoriteHandler
{
    public array $favoritesList = [];

    public UserDTO $userDTO;

    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly FootballRepositoryInterface $footballRepository,
        private readonly UserEntityManager $userEntityManager,
        private readonly UserRepository $userRepo,
        private readonly FavoriteMapper $favoriteMapper
    ) {
    }

    public function addUserFavorite(UserDTO $userDTO, string $id): void
    {
        $team = $this->footballRepository->getTeam($id);
        if (!empty($team) && !$this->getFavStatus($id)) {
            $this->userEntityManager->saveUserFavorite($userDTO, $this->favoriteMapper->createFavoriteDTO($team));
        }
        /*
        $team = $this->footballRepository->getTeam($id);
        if (!empty($team)) {
            $favoritesList[$userDTO->email] = $this->getUserFavorites($userDTO);
            if (!$this->getFavStatus( $id)) {
                $favoritesList[$userDTO->email] = $team;
                $this->userEntityManager->saveUserFavorites($userDTO, $favoritesList[$userDTO->email]);
            }
        }
        */
    }


    public function removeUserFavorite(UserDTO $userDTO, string $id): void
    {

      $this->userEntityManager->deleteUserFavorite($userDTO, $id);
    }

    public function getUserFavorites(UserDTO $userDTO): array
    {
        return $this->userRepo->getUserFavorites($userDTO);
    }

    public function getFavStatus(string $id): bool
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        return $this->userRepo->checkExistingFavorite($userDTO, $id);
    }


    public function moveUserFavorite(string $id, string $movement): void
    {
        $this->userDTO = $this->sessionHandler->getUserDTO();
        if ($movement === "up") {
            $this->moveFavUp($id);
        }
        if ($movement === "down") {
            $this->moveFavDown($id);
        }
    }

    private function moveFavUp(string $id): void
    {
        $userID = $this->userRepo->getUserID($this->userDTO);
        $minPosition = $this->userRepo->getUserMinFavoritePosition($this->userDTO);
        $position = $this->userRepo->getUserFavoritePosition($this->userDTO, $id);

        if ($position > $minPosition) {
            $favoritesArray = $this->userRepo->getUserFavorites($this->userDTO);


            foreach ($favoritesArray as $i => $favorite) {
                if ($favorite['favoritePosition'] === $position) {
                    $currentIndex = $i;
                    $prevIndex = $currentIndex - 1;

                    $currentFavoriteTeamID = $favorite['teamID'];
                    $prevFavTeamIndex = $favoritesArray[$prevIndex];
                    $prevFavTeamID = $prevFavTeamIndex['teamID'];

                    $this->userEntityManager->updateUserFavoritePosition(
                        $userID,
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

    private function moveFavDown(string $id): void
    {
        $userID = $this->userRepo->getUserID($this->userDTO);
        $maxPosition = $this->userRepo->getUserMaxFavoritePosition($this->userDTO);
        $position = $this->userRepo->getUserFavoritePosition($this->userDTO, $id);

        if ($position < $maxPosition) {
            $favoritesArray = $this->userRepo->getUserFavorites($this->userDTO);

            foreach ($favoritesArray as $i => $favorite) {
                if ($favorite['favoritePosition'] === $position) {
                    $currentIndex = $i;
                    $nextIndex = $currentIndex + 1;
                    if (isset($favoritesArray[$nextIndex])) {
                        $currentFavoriteTeamID = $favorite['teamID'];
                        $nextFavTeamIndex = $favoritesArray[$nextIndex];
                        $nextFavTeamID = $nextFavTeamIndex['teamID'];

                        $this->userEntityManager->updateUserFavoritePosition(
                            $userID,
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