<?php

namespace App\Controller;

use App\Model\FootballRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class CompetitionsController
{
    private FootballRepository $repository;
    private Environment $twig;
    private array $value;

    function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . "/../View");
        $this->repository = new FootballRepository();
        $this->twig = new Environment($loader);
        $this->value = [];
    }

    public function loadCompetitions(): void
    {
        $code = $_GET['name'];
        $page = $_GET['page'];
        if (isset($code) && isset($page) && $page === "competitions") {
            $teamsArray = $this->repository->getCompetition($code);
            $this->value['teams'] = $teamsArray;
        }
    }

    public function renderCompetitions(): void
    {
        echo $this->twig->render('competitions.twig', $this->value);
    }
}