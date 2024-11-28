<?php

declare(strict_types=1);

namespace App\Components\Shop\Business;

use App\Components\Shop\Business\Model\CalculatePrice;
use App\Components\Shop\Business\Model\CreateProducts;
use App\Components\Shop\Business\Model\ProductManager;
use App\Components\Shop\Persistence\DTOs\ProductDto;
use App\Components\Shop\Persistence\Mapper\ProductMapper;
use App\Components\Shop\Persistence\ProductRepository;
use App\Components\User\Persistence\DTOs\UserDTO;

readonly class ProductBusinessFacade
{
    public function __construct(
        private CreateProducts $createProducts,
        private CalculatePrice $calculatePrice,
        private ProductMapper $productMapper,
        private ProductManager $productManager,
        private ProductRepository $productRepository,
    ) {
    }

    public function getClubProducts(string $teamId): array
    {
        return $this->createProducts->createProducts($teamId);
    }

    public function createProduct(
        string $category,
        string $teamName,
        string $name,
        string $image,
        ?string $size,
        ?int $amount
    ): ProductDto {
        return $this->productMapper->createProductDto($category, $teamName, $name, $image, $size, $amount, null);
    }

    public function getProductPrice(ProductDto $productDto): ProductDto
    {
        return $this->calculatePrice->calculateProductPrice($productDto);
    }

    public function AddProductToCart(ProductDto $productDto): void
    {
        $this->productManager->addProductToCart($productDto);
    }

    public function getProducts(UserDTO $userDto): ?array
    {
        return $this->productRepository->getProductEntities($userDto);
    }

    public function increaseProductQuantity(string $productName): void
    {
        $this->productManager->increaseProductQuantity($productName);
    }

    public function decreaseProductQuantity(string $productName): void
    {
        $this->productManager->decreaseProductQuantity($productName);
    }

    public function deleteProduct(string $productName): void
    {
        $this->productManager->deleteProductFromCart($productName);
    }
}