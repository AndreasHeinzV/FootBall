<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Redirect;
use App\Core\View;
use App\Core\ViewInterface;
use App\Model\DTOs\PlayerDTO;
use App\Model\FootballRepositoryInterface;


readonly class PlayerController implements Controller
{


    public function __construct(private FootballRepositoryInterface $repository)
    {
    }

    public function load(ViewInterface $view): void
    {
        $id = $_GET['id'];
        if (isset($id)) {
            if ($this->repository->getPlayer($id) === null) {
                $redirect = new Redirect();
                $redirect->to('/?page=404');
                return;
            }

            $playerDTO = $this->repository->getPlayer($id);
            if (!($playerDTO === null)) {
                $view->setTemplate('player.twig');
                $view->addParameter('playerName', $playerDTO->name);
                $view->addParameter('playerData', $playerDTO);
            }
        }
    }
}