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
/*
    public SessionHandler $sessionHandler;
    public FootballRepositoryInterface $footballRepository;
    public UserEntityManager $userEntityManager;
*/

    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly FootballRepositoryInterface $footballRepository,
        private readonly UserEntityManager $userEntityManager,
        private readonly UserRepository $userRepo)
    {
        /*
        $this->footballRepository = $footballRepository;
        $this->sessionHandler = $sessionHandler;
        $this->userEntityManager = $userEntityManager;
        */
    }

    public function addFavorite(UserDTO $userDTO, string $id): void
    {
        $team = $this->footballRepository->getTeam($id);
        $this->favoritesList[$userDTO->email] = $team;
        $this->userEntityManager->saveUserFavorites($userDTO, $this->favoritesList[$userDTO->email]);
    }
/*
    //TODO add save
    public
*/

    public function removeFavorite(UserDTO $userDTO, string $id): void
    {
        $this->favoritesList = $this->userRepo->getFavorites();
        foreach ($this->favoritesList[$userDTO->email] as $key =>$favorite) {
            if($favorite['teamID'] === (int)$id) {
                unset($this->favoritesList[$userDTO->email][$key]);
            }
        }
        $this->userEntityManager->saveFavorites( $this->favoritesList);
    }

    public function getFavorites(UserDTO $userDTO): array
    {
        $this->favoritesList[$userDTO->email] = $this->userRepo->getUserFavorites($userDTO);
        return $this->favoritesList[$userDTO->email] ?? [];
    }

    public function getFavStatus(string $id): bool
    {
        $userDTO = $this->sessionHandler->getUserDTO();
        $this->favoritesList[$userDTO->email] = $this->userRepo->getUserFavorites($userDTO);
        if (isset($this->favoritesList[$userDTO->email])) {
            foreach ($this->favoritesList[$userDTO->email] as $favorite) {
                if ($favorite['teamID'] === (int)$id) {
                    return true;
                }
            }
        }
        return false;
    }

}