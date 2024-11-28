<?php

declare(strict_types=1);

namespace App\Components\Shop\Business\Model;

use App\Components\Database\Persistence\Entity\ProductEntity;
use App\Components\Shop\Persistence\DTOs\ProductDto;
use App\Components\Shop\Persistence\ProductEntityManager;
use App\Components\Shop\Persistence\ProductRepository;
use App\Core\SessionHandler;

readonly class ProductManager
{
    public function __construct(
        private SessionHandler $sessionHandler,
        private ProductRepository $productRepository,
        private ProductEntityManager $entityManager
    ) {
    }

    public function addProductToCart(ProductDto $productDto): void
    {
        $userDto = $this->sessionHandler->getUserDTO();
        $productEntity = $this->productRepository->getProductEntityByName($userDto, $productDto->name);
        if ($productEntity instanceof ProductEntity) {
            $this->entityManager->manipulateProductEntityAmount($productEntity, $productDto->amount);
        } else {
            $this->entityManager->saveProductEntity($productDto, $userDto);
        }
    }

    public function increaseProductQuantity(string $productName): void
    {
        $userDto = $this->sessionHandler->getUserDTO();
        $productEntity = $this->productRepository->getProductEntityByName($userDto, $productName);
        if ($productEntity instanceof ProductEntity) {
            $this->entityManager->manipulateProductEntityAmount($productEntity, +1);
        }
    }

    public function decreaseProductQuantity(string $productName): void
    {
        $userDto = $this->sessionHandler->getUserDTO();
        $productEntity = $this->productRepository->getProductEntityByName($userDto, $productName);
        if ($productEntity instanceof ProductEntity) {
            if ($productEntity->getAmount() > 1) {
                $this->entityManager->manipulateProductEntityAmount($productEntity, -1);
            } else {
                $this->deleteProductFromCart($productName);
            }
        }
    }

    public function deleteProductFromCart(string $productName): void
    {
        $userDto = $this->sessionHandler->getUserDTO();
        $productEntity = $this->productRepository->getProductEntityByName($userDto, $productName);
        if ($productEntity instanceof ProductEntity) {
            $this->entityManager->deleteProductEntity($userDto, $productEntity->getProductId());
        }
    }
}