<?php

declare(strict_types=1);

namespace App\Components\UserFavorite\Communication\Controller;


use App\Components\UserFavorite\Business\UserFavoriteBusinessFacadeInterface;
use App\Core\SessionHandler;
use App\Core\ViewInterface;


readonly class FavoriteController implements FavoriteControllerInterface
{


    public function __construct(
        private SessionHandler $sessionHandler,
        private UserFavoriteBusinessFacadeInterface $userFavoriteBusinessFacade,
    ) {
    }


    public function load(ViewInterface $view): void
    {
        $user = $this->sessionHandler->getUserDTO();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userFavoriteBusinessFacade->manageFavoriteInput($_POST);
        }
        $view->setTemplate('favorites.twig');
        $view->addParameter('favorites', $this->userFavoriteBusinessFacade->getUserFavorites($user));
    }
}