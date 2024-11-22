<?php

declare(strict_types=1);

namespace App\Components\Shop\Business;

use App\Components\Shop\Business\Model\CalculatePrice;
use App\Components\Shop\Business\Model\CreateProducts;
use App\Components\Shop\Business\Model\ProductManager;
use App\Components\Shop\Persistence\DTOs\ProductDto;
use App\Components\Shop\Persistence\Mapper\ProductMapper;

readonly class ProductBusinessFacade
{
    public function __construct(
        private CreateProducts $createProducts,
        private CalculatePrice $calculatePrice,
        private ProductMapper $productMapper,
        private ProductManager $productManager
    ) {
    }

    public function getClubProducts(string $teamId): array
    {
        return $this->createProducts->createProducts($teamId);
    }

    public function createProduct(
        string $category,
        string $name,
        string $image,
        ?string $size,
        ?int $amount
    ): ProductDto {
        return $this->productMapper->createProductDto($category, $name, $image, $size, $amount);
    }

    public function getProductPrice(ProductDto $productDto): ProductDto
    {
        return $this->calculatePrice->calculateProductPrice($productDto);
    }

    public function AddProductToCart(ProductDto $productDto): void
    {
        $this->productManager->addProductToCart($productDto);
    }

    public function RemoveProductFromCart(ProductDto $productDto): void{
        $this->productManager->removeProductFromCart($productDto);
    }

}