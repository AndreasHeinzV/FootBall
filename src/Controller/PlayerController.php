<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;
use App\Model\FootballRepositoryInterface;


class PlayerController implements Controller
{
    private FootballRepositoryInterface $repository;
    private array $value;

    public function __construct(FootballRepositoryInterface $repository)
    {
        $this->repository = $repository;
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