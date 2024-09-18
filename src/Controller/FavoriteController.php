<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\FavoriteHandler;
use App\Core\ManageFavorites;
use App\Core\SessionHandler;
use App\Core\ViewInterface;
use App\Model\DTOs\UserDTO;

readonly class FavoriteController implements Controller
{


    public function __construct(
        private SessionHandler $sessionHandler,
        private FavoriteHandler $favoriteHandler,
        private ManageFavorites $manageFavorites,
    ) {
    }


    public function load(ViewInterface $view): void
    {
        $user = $this->sessionHandler->getUserDTO();
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
            $this->manageFavorites->manageFav($_POST);
        }
        $view->setTemplate('favorites.twig');
        $view->addParameter('favorites', $this->favoriteHandler->getUserFavorites($user) ?? []);
    }


}