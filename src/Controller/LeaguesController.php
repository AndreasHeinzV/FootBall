<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Core\ViewInterface;
use App\Model\FootballRepository;
use App\Model\FootballRepositoryInterface;

class LeaguesController implements Controller
{
    private FootballRepository $repository;

    private array $competitions;
    private string $code;

    public function __construct(FootballRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function load(ViewInterface $view): void
    {
        if (isset($_GET['name'])) {
            $this->code = $_GET['name'];
            $this->competitions= $this->repository->getCompetition($this->code);
        }
        $this->setupView($view);
    }
    
    private function setupView(ViewInterface $view): void
    {
        $view->setTemplate('competitions.twig');
        $view->addParameter('name', $this->code ?? '');
        $view->addParameter('teams', $this->competitions ?? []);

    }
}