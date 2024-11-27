<?php

declare(strict_types=1);

namespace App\Components\Shop\Communication;

use App\Components\Shop\Business\ProductBusinessFacade;
use App\Core\SessionHandler;
use App\Core\ViewInterface;

class CartController
{

    public function __construct(
        private SessionHandler $sessionHandler,
        private ProductBusinessFacade $productBusinessFacade,
    ) {
    }

    public function load(ViewInterface $view): void{

        $user = $this->sessionHandler->getUserDTO();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //    $this->userFavoriteBusinessFacade->manageFavoriteInput($_POST);
        }


        $view->setTemplate('cart.twig');
        $view->addParameter('products', $this->productBusinessFacade->getProducts($user));
    }
}