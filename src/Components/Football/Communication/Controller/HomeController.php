<?php

declare(strict_types=1);

namespace App\Components\Football\Communication\Controller;


use App\Components\Football\Business\Model\FootballBusinessFacadeInterface;
use App\Core\ViewInterface;

readonly class HomeController implements FootballControllerInterface
{


    public function __construct(
        private FootballBusinessFacadeInterface $footballBusinessFacade
    ) {
    }


    public function load(ViewInterface $view): void
    {
        $view->setTemplate('home.twig');
        $view->addParameter('leagues', $this->footballBusinessFacade->getLeagues());
    }
}