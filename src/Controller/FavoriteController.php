<?php

declare(strict_types=1);

namespace App\Controller;

use App\Components\UserFavorite\Business\UserFavoriteHandler;
use App\Components\UserFavorite\Business\UserManageFavorites;
use App\Core\SessionHandler;
use App\Core\ViewInterface;


readonly class FavoriteController implements Controller
{


    public function __construct(
        private SessionHandler $sessionHandler,
        private UserFavoriteHandler $favoriteHandler,
        private UserManageFavorites $manageFavorites,
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