<?php

declare(strict_types=1);

namespace App\Components\Football\Communication\Controller;

use App\Components\Football\Business\Model\FootballBusinessFacadeInterface;
use App\Core\RedirectInterface;
use App\Core\ViewInterface;


readonly class PlayerController implements FootballControllerInterface
{


    public function __construct(
        private FootballBusinessFacadeInterface $footballBusinessFacade,
        private RedirectInterface $redirect
    ) {
    }

    public function load(ViewInterface $view): void
    {
        if (!isset($_GET['id'])) {
         $this->redirect->to('/');
            return;
        }

        $id = $_GET['id'];
        if ($this->footballBusinessFacade->getPlayer($id) === null) {
            $this->redirect->to('/?page=404');
            return;
        }

        $playerDTO = $this->footballBusinessFacade->getPlayer($id);
        if (!($playerDTO === null)) {
            $view->setTemplate('player.twig');
            $view->addParameter('playerName', $playerDTO->name);
            $view->addParameter('playerData', $playerDTO);
        }
    }
}