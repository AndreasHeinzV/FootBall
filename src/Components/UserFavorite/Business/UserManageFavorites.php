<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Business;

use App\Core\FavoriteHandler;
use App\Core\SessionHandler;
use App\Model\DTOs\UserDTO;

class UserManageFavorites
{
    private UserDTO $user;

    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly FavoriteHandler $favoriteHandler
    ) {
    }

    public function manageFav(array $input): void
    {
        $this->user = $this->sessionHandler->getUserDTO();
        foreach ($input as $keyValue => $value) {
            switch (true) {
                case ($keyValue === 'add'):
                    $this->handleAdd($value);
                    break;
                case ($keyValue === 'delete'):
                    $this->handleRemove($value);
                    break;

                case ($keyValue === 'up'):
                    $this->handleUp($value);
                    break;

                case ($keyValue === 'down'):
                    $this->handleDown($value);
                    break;
            }
        }
    }

    private function handleAdd(string $id): void
    {
        $this->favoriteHandler->addUserFavorite($this->user, $id);
    }

    private function handleRemove(string $id): void
    {
        $this->favoriteHandler->removeUserFavorite($this->user, $id);
    }

    private function handleUp(string $id): void
    {
        $this->favoriteHandler->moveUserFavorite($id, "up");
    }

    private function handleDown(string $id): void
    {
        $this->favoriteHandler->moveUserFavorite($id, "down");
    }

}
