<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\FootballRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class LeaguesController implements Controller
{
    private FootballRepository $repository;
    private Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . "/../View");
        $this->repository = new FootballRepository();
        $this->twig = new Environment($loader);
    }

    public function load(): void
    {
        if (!isset($_GET['name'])) {
            throw new \RuntimeException("No name specified");
        }

        $code = $_GET['name'];
        $teamsArray = $this->repository->getCompetition($code);
        $value['teams'] = $teamsArray;

        echo $this->twig->render('competitions.twig', $value);
    }





}