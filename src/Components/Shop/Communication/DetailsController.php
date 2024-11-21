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
            $productDto = $this->productBusinessFacade->createProduct($category, $name, $image);
            $view->addParameter('productDto', $productDto);
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['size'], $_POST['category'], $_POST['name'], $_POST['imageLink'])) {
            $category = $_POST['category'];
            $name = $_POST['name'];
            $image = $_POST['imageLink'];
            $size = $_POST['size'];

            $productDto = $this->productBusinessFacade->createProduct($category, $name, $image);
            $productDto->size = $size;
            $productDto = $this->productBusinessFacade->getProductPrice($productDto);
            $view->addParameter('productDto', $productDto);
        }


        $view->setTemplate('details.twig');
    }

}