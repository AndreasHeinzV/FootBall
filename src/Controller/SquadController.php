<?php

namespace App\Controller;


use App\Model\FootballRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class SquadController
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

    public function loadSquad(): void
    {
        $id = $_GET['id'];
        if (isset($id) && $_GET['page'] === "team") {
            $squadArray = $this->repository->getSquad($id);
          $this->value['players'] = $squadArray;
        }
    }
    public function renderSquad() :void{
        echo $this->twig->render('squad.twig', $this->value);
    }

}