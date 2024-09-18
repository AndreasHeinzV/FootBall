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


    private array $competitions;
    private string $code;

    public function __construct(private readonly FootballRepositoryInterface $repository)
    {
    }

    public function load(ViewInterface $view): void
    {

        if(!isset($_GET['name'])) {
            $redirect = new Redirect();
            $redirect->to('/');
        }

            $this->code = $_GET['name'];
            $this->competitions = $this->repository->getCompetition($this->code);

        $view->setTemplate('competitions.twig');
        $view->addParameter('name', $this->code ?? '');
        $view->addParameter('teams', $this->competitions);
    }


}