<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\FavoriteHandler;
use App\Core\ManageFavorites;
use App\Core\SessionHandler;
use App\Core\View;
use App\Core\ViewInterface;
use App\Model\DTOs\UserDTO;
use App\Model\FootballRepository;

class TeamController implements Controller
{
    // private FootballRepository $repository;
    //private FavoriteHandler  $favoriteHandler;


    private array $squadArray;

    private string $id;
    public UserDTO $user;

    public function __construct(
        private readonly FootballRepository $repository,
        private readonly FavoriteHandler $favoriteHandler,
        private ManageFavorites $manageFavorites,
    ) {
//        $this->repository = $repository;
        //$this->favoriteHandler = $favoriteHandler;
    }

    public function load(ViewInterface $view): void
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)) {
            $this->manageFavorites->manageFav($_POST);
        }

        $this->id = $_GET['id'];
        if (isset($this->id)) {
            $this->squadArray = $this->repository->getTeam($this->id);
        }

        $this->setupView($view);
    }

    private function setupView(Viewinterface $view): void
    {
        $view->setTemplate('team.twig');
        $view->addParameter('players', $this->squadArray ?? []);
        $view->addParameter('favoriteStatus', $this->favoriteHandler->getFavStatus($this->id) ?? false);
    }
}