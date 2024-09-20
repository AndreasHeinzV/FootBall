<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Redirect;
use App\Core\View;
use App\Core\ViewInterface;
use App\Model\FootballRepository;
use App\Model\FootballRepositoryInterface;

class LeaguesController implements Controller
{


    public function __construct(private readonly FootballRepositoryInterface $repository)
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
        $competition = $this->repository->getCompetition($code);
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