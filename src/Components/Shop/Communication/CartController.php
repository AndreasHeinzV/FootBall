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

    public function load(ViewInterface $view): void
    {
        $user = $this->sessionHandler->getUserDTO();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productName = $_POST['productName'] ?? '';
            if (isset($_POST['changeAmount'])) {
                if ($_POST['changeAmount'] === 'increase') {
                    $this->productBusinessFacade->increaseProductQuantity($productName);
                } elseif ($_POST['changeAmount'] === 'decrease') {
                    $this->productBusinessFacade->decreaseProductQuantity($productName);
                }
            }

            if (isset($_POST['deleteProduct']) && $_POST['deleteProduct'] === 'true') {
                $this->productBusinessFacade->deleteProduct($productName);
            }
        }
        $view->setTemplate('cart.twig');
        $view->addParameter('products', $this->productBusinessFacade->getProducts($user));
    }
}