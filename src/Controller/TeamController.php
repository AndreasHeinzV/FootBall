<?php

declare(strict_types=1);

namespace App\Controller;

use App\Components\UserFavorite\Business\UserFavoriteHandler;
use App\Components\UserFavorite\Business\UserManageFavorites;
use App\Core\FavoriteHandler;
use App\Core\ManageFavorites;
use App\Core\Redirect;
use App\Core\SessionHandler;
use App\Core\View;
use App\Core\ViewInterface;
use App\Model\DTOs\UserDTO;
use App\Model\FootballRepository;

readonly class TeamController implements Controller
{

    public function __construct(
        private FootballRepository $repository,
        private UserFavoriteHandler $favoriteHandler,
        private UserManageFavorites $manageFavorites,
    ) {
    }

    public function load(ViewInterface $view): void
    {

        if (isset($_POST)) {
            $this->manageFavorites->manageFav($_POST);
        }

        if (!isset($_GET['id'])) {
            $redirect = new Redirect();
            $redirect->to('/');
            return;
        }

        $id = $_GET['id'];
        $team = $this->repository->getTeam($id);
        if (empty($team)) {
            $redirect = new Redirect();
            $redirect->to("/?page=404");
            return;
        }

        $view->setTemplate('team.twig');
        $view->addParameter('players', $team);
        $view->addParameter('favoriteStatus', $this->favoriteHandler->getFavStatus($id));
    }
}