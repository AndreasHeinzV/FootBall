<?php

declare(strict_types=1);

namespace App\Components\Shop\Communication;

use App\Components\Shop\Business\ProductBusinessFacade;
use App\Components\Shop\Persistence\DTOs\ProductDto;
use App\Core\ViewInterface;

class DetailsController
{


    public function __construct(private ProductBusinessFacade $productBusinessFacade)
    {
    }


    public function load(ViewInterface $view): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['category'], $_GET['name'], $_GET['imageLink'])) {
            $category = $_GET['category'];
            $name = $_GET['name'];
            $image = $_GET['imageLink'];
            $productDto = $this->productBusinessFacade->createProduct($category, $name, $image, null, null);
            $view->addParameter('productDto', $productDto);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category'], $_POST['name'], $_POST['imageLink']) && $_POST['calculatePriceButton'] === 'submit') {
            $customName = $_POST['customName'] ?? null;
            $size = $_POST['size'] ?? null;
            if (isset($_POST['amount']) && ctype_digit($_POST['amount']) && $_POST['amount'] > 0) {
                $amount = (int)$_POST['amount'];
            } else {
                $amount = 1;
            }
            $category = $_POST['category'];
            $name = $_POST['name'];
            $image = $_POST['imageLink'];

            if ($customName !== null) {
                $name = $customName . " " . $category;
            }

            $productDto = $this->productBusinessFacade->createProduct($category, $name, $image, $size, $amount);
            $productDto = $this->productBusinessFacade->getProductPrice($productDto);
            $view->addParameter('productDto', $productDto);
        }



        $view->setTemplate('details.twig');
    }

}