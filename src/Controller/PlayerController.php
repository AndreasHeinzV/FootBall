<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Core\ViewInterface;
use App\Model\DTOs\PlayerDTO;
use App\Model\FootballRepositoryInterface;


class PlayerController implements Controller
{
    private FootballRepositoryInterface $repository;

    public function __construct(FootballRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function load(ViewInterface $view): void
    {
        $id = $_GET['id'];
        if (isset($id)) {
            $playerDTO = $this->repository->getPlayer($id);

            $view->setTemplate('player.twig');
            $view->addParameter('playerName', $playerDTO->name);
            $view->addParameter('playerData', $playerDTO);
        }
    }
}