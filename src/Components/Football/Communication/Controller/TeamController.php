<?php

declare(strict_types=1);

namespace App\Components\Football\Communication\Controller;

use App\Components\Football\Business\Model\FootballBusinessFacadeInterface;
use App\Components\UserFavorite\Business\UserFavoriteBusinessFacadeInterface;
use App\Core\Redirect;
use App\Core\ViewInterface;

readonly class TeamController implements FootballControllerInterface
{

    public function __construct(
        private FootballBusinessFacadeInterface $footballBusinessFacade,
        private UserFavoriteBusinessFacadeInterface $userFavoriteBusinessFacade,
    ) {
    }

    public function load(ViewInterface $view): void
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $this->userFavoriteBusinessFacade->manageFavoriteInput($_POST);
        }

        if (!isset($_GET['id'])) {
            $redirect = new Redirect();
            $redirect->to('/');
            return;
        }

        $teamId = $_GET['id'];
        $team = $this->footballBusinessFacade->getTeam($teamId);
        if (empty($team)) {
            $redirect = new Redirect();
            $redirect->to("/?page=404");
            return;
        }

        $view->setTemplate('team.twig');
        $view->addParameter('players', $team);
        $view->addParameter('favoriteStatus', $this->userFavoriteBusinessFacade->getFavoriteStatus($teamId));
    }
}