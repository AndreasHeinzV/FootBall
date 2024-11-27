<?php

declare(strict_types=1);

namespace App\Components\Shop\Communication;

use App\Components\Shop\Business\ProductBusinessFacade;
use App\Components\Shop\Persistence\DTOs\ProductDto;
use App\Core\Redirect;
use App\Core\ViewInterface;

class DetailsController
{


    public function __construct(private ProductBusinessFacade $productBusinessFacade)
    {
    }


    public function load(ViewInterface $view): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['category'], $_GET['name'], $_GET['imageLink'], $_GET['teamName'])) {
            $category = $_GET['category'];
            $name = $_GET['name'];
            $image = $_GET['imageLink'];
            $teamName = $_GET['teamName'];
            $productDto = $this->productBusinessFacade->createProduct($category, $teamName, $name, $image, null, null);
            $view->addParameter('productDto', $productDto);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['category'], $_POST['imageLink'], $_POST['teamName'])) {
            $customName = $_POST['customName'] ?? null;
            $size = $_POST['size'] ?? null;
            $amount = 1; // Default amount

            if (isset($_POST['amount']) && ctype_digit($_POST['amount']) && $_POST['amount'] > 0) {
                $amount = (int)$_POST['amount'];
            }
            $teamName = $_POST['teamName'];
            $category = $_POST['category'];
            $name = $_POST['name'];
            $image = $_POST['imageLink'];

            if ($customName !== null) {
                $name = $customName . " " . $category;
            }

            $productDto = $this->productBusinessFacade->createProduct($category, $teamName, $name, $image, $size, $amount);

            if (isset($_POST['calculatePriceButton']) && $_POST['calculatePriceButton'] === 'submit') {
                $productDto = $this->productBusinessFacade->getProductPrice($productDto);
                $view->addParameter('productDto', $productDto);
            }

            if (isset($_POST['addToCartButton']) && $_POST['addToCartButton'] === 'addToCart') {
                $productDto = $this->productBusinessFacade->getProductPrice($productDto);
                $this->productBusinessFacade->AddProductToCart($productDto);
                $view->addParameter('message', "Product added to cart.");
            }
        }


        //todo write function for button to save favorite Dto in db
        //todo entity and repo are rdy to go
        //todo testcase is rdy just needs to be adjusted
        $view->setTemplate('details.twig');
    }
}