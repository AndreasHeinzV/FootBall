<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;
use App\Model\FootballRepository;
use App\Model\FootballRepositoryInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TeamController implements Controller
{
    private FootballRepository $repository;
    private Environment $twig;
    private array $value;


    public function __construct(Environment $twig, FootballRepository $repository)
    {
        $this->repository = $repository;
        $this->twig = $twig;
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