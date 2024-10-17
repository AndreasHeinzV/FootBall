<?php

declare(strict_types=1);

namespace App\Components\Football\Communication\Controller;

use App\Components\Football\Business\Model\FootballBusinessFacadeInterface;
use App\Core\Redirect;
use App\Core\ViewInterface;

readonly class LeaguesController implements FootballControllerInterface
{


    public function __construct(private FootballBusinessFacadeInterface $footballBusinessFacade)
    {
    }

    public function load(ViewInterface $view): void
    {
        if (!isset($_GET['name'])) {
            $redirect = new Redirect();
            $redirect->to('/');
            return;
        }

        $code = $_GET['name'];
        $competition = $this->footballBusinessFacade->getCompetition($code);
        if (empty($competition)) {
            $redirect = new Redirect();
            $redirect->to("/?page=404");
            return;
        }

        $view->setTemplate('competitions.twig');
       // $view->addParameter('name', $code);
        $view->addParameter('teams', $competition);
    }


}