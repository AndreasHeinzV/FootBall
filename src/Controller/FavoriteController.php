<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\FavoriteHandler;
use App\Core\SessionHandler;
use App\Core\ViewInterface;
use App\Model\DTOs\UserDTO;

class FavoriteController implements Controller
{
    private UserDTO $user;

    public function __construct(
        private SessionHandler $sessionHandler,
        private FavoriteHandler $favoriteHandler
    ) {
    }


    public function load(ViewInterface $view): void
    {
        $this->user = $this->sessionHandler->getUserDTO();
        $this->favoriteHandler->getFavorites($this->user);

        $this->handlePost();
        $this->setupView($view);
    }

    private function handlePost(): void
    {
        $link = $_GET;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        if ($_POST['favorite'] === 'add') {

        $this->favoriteHandler->addFavorite($this->user, );
        }
        if ($_POST['favorite'] === 'delete') {

            $this->favoriteHandler->removeFavorite($this->user, );
        }
    }

    private function setupView(ViewInterface $view): void
    {
        $view->setTemplate('favorites.twig');
        $view->addParameter('favorites', $this->favoriteHandler->getFavorites($this->user) ?? []);
    }
}