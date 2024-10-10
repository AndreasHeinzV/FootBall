<?php

declare(strict_types=1);

namespace App\Core;

use App\Model\DTOs\CompetitionDTO;
use App\Model\DTOs\TeamDTO;
use App\Model\DTOs\UserDTO;
use App\Model\FootballRepositoryInterface;
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
        private readonly UserRepository $userRepo
    ) {
    }

    public function addUserFavorite(UserDTO $userDTO, string $id): void
    {
        $team = $this->footballRepository->getTeam($id);
        if (!empty($team)) {
            $this->userEntityManager->saveUserFavorites($userDTO, $team);
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
        $this->favoritesList = $this->userRepo->getFavorites();
        foreach ($this->favoritesList[$userDTO->email] as $key => $favorite) {
            if ($favorite['teamID'] === (int)$id) {
                unset($this->favoritesList[$userDTO->email][$key]);
            }
        }
        $this->userEntityManager->saveFavorites($this->favoritesList);
    }

    public function getUserFavorites(UserDTO $userDTO): array
    {
        return $this->userRepo->getUserFavorites($userDTO);

        /*

        $favoritesList[$userDTO->email] = $this->userRepo->getUserFavorites($userDTO);
        return $favoritesList[$userDTO->email] ?? [];
        */
    }

    public function getFavStatus(string $id): bool
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        $this->favoritesList[$userDTO->email] = $this->userRepo->getUserFavorites($userDTO);
        if (!empty($this->favoritesList[$userDTO->email])) {
            foreach ($this->favoritesList[$userDTO->email] as $favorite) {
                if ($favorite['teamID'] === (int)$id) {
                    return true;
                }
            }
        }
        return false;
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
        $this->userEntityManager->saveFavorites($this->favoritesList);
    }

    private function moveFavUp(string $id): void
    {
        $this->favoritesList = $this->userRepo->getFavorites();
        $tempArray = [];
        $length = count($this->favoritesList[$this->userDTO->email]);
        foreach ($this->favoritesList[$this->userDTO->email] as $key => $favorite) {
            if ($favorite['teamID'] === (int)$id) {
                $tempArray = $favorite;
                if ((int)$key !== 0 && $length >= 2) {
                    $this->favoritesList[$this->userDTO->email][$key] = $this->favoritesList[$this->userDTO->email][$key - 1];
                    $this->favoritesList[$this->userDTO->email][$key - 1] = $tempArray;
                }
            }
        }
    }

    private function moveFavDown(string $id): void
    {
        $this->favoritesList = $this->userRepo->getFavorites();
        $tempArray = [];
        $length = count($this->favoritesList[$this->userDTO->email]);
        foreach ($this->favoritesList[$this->userDTO->email] as $key => $favorite) {
            if ($favorite['teamID'] === (int)$id) {
                $tempArray = $favorite;
                if ((int)$key !== $length && $length >= 2) {
                    $this->favoritesList[$this->userDTO->email][$key] = $this->favoritesList[$this->userDTO->email][$key + 1];
                    $this->favoritesList[$this->userDTO->email][$key + 1] = $tempArray;
                }
            }
        }
    }
}