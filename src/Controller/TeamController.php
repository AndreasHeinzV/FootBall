<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;
use App\Model\FootballRepository;

class TeamController implements Controller
{
    private FootballRepository $repository;

    private array $value;


    public function __construct( FootballRepository $repository)
    {
        $this->repository = $repository;
        $this->value = [];
        //$this->repository = new FootballRepository();
    }

    public function load(ViewInterface $view): array
    {
        $id = $_GET['id'];
        if (isset($id)) {
            $squadArray = $this->repository->getTeam($id);
            $this->value['players'] = $squadArray;
            //echo $this->twig->render('team.twig', $this->value);
        }
        return $this->value;
    }
}