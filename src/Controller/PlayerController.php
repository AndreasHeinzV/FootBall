<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;
use App\Model\FootballRepository;
use App\Model\FootballRepositoryInterface;
use App\Model\RepositoryInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


class PlayerController implements Controller
{
    private FootballRepository $repository;
    private Environment $twig;
    private array $value;

    public function __construct(Environment $twig, FootballRepositoryInterface $repository)
    {
        $this->repository = $repository;
        $this->twig = $twig;
        //  $this->repository = new FootballRepository();
        $this->value = [];
    }

    public function load(ViewInterface $view): array
    {
        $id = $_GET['id'];
        if (isset($id)) {
            $playerArray = $this->repository->getPlayer($id);
            $playerName = array_shift($playerArray);
            $this->value['playerName'] = $playerName;
            $this->value['playerData'] = $playerArray;
            //echo $this->twig->render('player.twig', $this->value);
            return $this->value;
        }
        return [];
    }
}