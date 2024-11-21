<?php

declare(strict_types=1);

namespace App\Components\Shop\Business;

use App\Components\Shop\Business\Model\CalculatePrice;
use App\Components\Shop\Business\Model\CreateProducts;
use App\Components\Shop\Persistence\DTOs\ProductDto;
use App\Components\Shop\Persistence\ProductMapper;

readonly class ProductBusinessFacade
{
    public function __construct(
        private CreateProducts $createProducts,
        private CalculatePrice $calculatePrice,
        private ProductMapper $productMapper
    ) {
    }

    public function getClubProducts(string $teamId): array
    {
        return $this->createProducts->createProducts($teamId);
    }

    public function createProduct(mixed $category, mixed $name, mixed $image): ProductDto
    {
        return $this->productMapper->createProductDto($category, $name, $image);
    }

    public function getProductPrice(ProductDto $productDto): ProductDto
    {
        return $this->calculatePrice->calculateProductPrice($productDto);
    }

}