<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\SessionHandler;
use App\Core\ViewInterface;
use App\Model\FootballRepository;

class HomeController implements Controller
{
    private FootballRepository $repository;
    public SessionHandler $sessionHandler;

    public function __construct(FootballRepository $repository,  SessionHandler $sessionHandler)
    {
        $this->repository = $repository;
        $this->sessionHandler = $sessionHandler;
    }


    public function load(ViewInterface $view): void
    {
       $this->setupView($view);
    }

    private function setupView(ViewInterface $view): void
    {
        $view->setTemplate('home.twig');
        $view->addParameter('leagues', $this->repository->getLeagues() ?? []);

    }
}