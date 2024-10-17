<?php

declare(strict_types=1);

namespace App\Components\Football\Communication\Controller;

use App\Components\Football\Business\Model\FootballBusinessFacadeInterface;
use App\Core\Redirect;
use App\Core\ViewInterface;


readonly class PlayerController implements FootballControllerInterface
{


    public function __construct(private FootballBusinessFacadeInterface $footballBusinessFacade)
    {
    }

    public function load(ViewInterface $view): void
    {
        $id = $_GET['id'];
        if (isset($id)) {
            if ($this->footballBusinessFacade->getPlayer($id) === null) {
                $redirect = new Redirect();
                $redirect->to('/?page=404');
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
}