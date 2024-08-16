<?php

declare(strict_types=1);
namespace App\Controller;
use App\Model\FootballRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TeamController implements Controller
{
    private FootballRepository $repository;
    private Environment $twig;
    private array $value;


    function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . "/../View");
        $this->twig = new Environment($loader);
        $this->value =[];
        $this->repository = new FootballRepository();

    }

    public function load(): void
    {
        $id = $_GET['id'];
        if (isset($id) && $_GET['page'] === "team") {
            $squadArray = $this->repository->getTeam($id);
          $this->value['players'] = $squadArray;
        }
        $this->renderSquad();
    }
    private function renderSquad() :void{
        echo $this->twig->render('Team.twig', $this->value);
    }
}