<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Core\ViewInterface;
use App\Model\FootballRepository;

class TeamController implements Controller
{
    private FootballRepository $repository;

    private array $squadArray;

    public function __construct(FootballRepository $repository)
    {
        $this->repository = $repository;
    }

    public function load(ViewInterface $view): void
    {
        $id = $_GET['id'];
        if (isset($id)) {
            $this->squadArray = $this->repository->getTeam($id);
        }
        $this->setupView($view);
    }

    private function setupView(Viewinterface $view): void
    {

        $view->setTemplate('team.twig');
        $view->addParameter('players', $this->squadArray ?? []);
    }
}