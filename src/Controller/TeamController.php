<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\FootballRepository;
use App\Model\FootballRepositoryInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TeamController implements Controller
{
    private FootballRepository $repository;
    private Environment $twig;
    private array $value;


    public function __construct( )
    {
        $this->repository = new FootballRepository();
        $loader = new FilesystemLoader(__DIR__ . "/../View");
        $this->twig = new Environment($loader);
        $this->value = [];
        //$this->repository = new FootballRepository();
    }

    public function load(): void
    {
        $id = $_GET['id'];
        if (isset($id)) {
            $squadArray = $this->repository->getTeam($id);
            $this->value['players'] = $squadArray;
            $this->renderSquad();
        }
    }

    private function renderSquad(): void
    {
        echo $this->twig->render('Team.twig', $this->value);
    }
}