<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\SessionHandler;
use App\Core\ViewInterface;
use App\Model\FootballRepository;

readonly class HomeController implements Controller
{


    public function __construct(
        private FootballRepository $repository
    ) {
    }


    public function load(ViewInterface $view): void
    {
        $view->setTemplate('home.twig');
        $view->addParameter('leagues', $this->repository->getLeagues() ?? []);
    }
}