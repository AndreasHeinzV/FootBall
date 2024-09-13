<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\FavoriteHandler;
use App\Core\ManageFavorites;
use App\Core\SessionHandler;
use App\Core\ViewInterface;
use App\Model\DTOs\UserDTO;

class FavoriteController implements Controller
{
    private UserDTO $user;

    public function __construct(
        private readonly SessionHandler $sessionHandler,
        private readonly FavoriteHandler $favoriteHandler,
        private readonly ManageFavorites $manageFavorites,
    ) {
    }


    public function load(ViewInterface $view): void
    {
        $this->user = $this->sessionHandler->getUserDTO();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
            $this->manageFavorites->manageFav($_POST);
        }
        $this->setupView($view);
    }

    private function setupView(ViewInterface $view): void
    {
        $view->setTemplate('favorites.twig');
        $view->addParameter('favorites', $this->favoriteHandler->getUserFavorites($this->user) ?? []);
    }
}