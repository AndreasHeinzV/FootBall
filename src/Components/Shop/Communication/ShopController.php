<?php

declare(strict_types=1);

namespace App\Components\Shop\Communication;

use App\Components\Shop\Business\ProductBusinessFacade;
use App\Core\ViewInterface;

readonly class ShopController
{
    public function __construct(private ProductBusinessFacade $productBusinessFacade)
    {
    }

    public function load(ViewInterface $view): void
    {
        $products = [];

        if (isset($_GET['teamId'])) {
          //  $page = $_GET['page'];
            $teamId = $_GET['teamId'];
            $teamName = $_GET['teamName'];
            $products = $this->productBusinessFacade->getClubProducts($teamId);
            $view->addParameter('teamName', $teamName);
        }

        $view->setTemplate('shop.twig');
        $view->addParameter('products', $products);
    }
}