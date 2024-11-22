<?php

declare(strict_types=1);

namespace App\Components\Shop\Business\Model;

use App\Components\Shop\Persistence\DTOs\ProductDto;
use App\Components\Shop\Persistence\ProductEntityManager;
use App\Components\Shop\Persistence\ProductRepository;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Core\SessionHandler;

class ProductManager
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
        $productEntity = $this->productRepository->getProductEntity($productDto, $userDto);
        $this->entityManager->saveProductEntity($productDto, $userDto);
    }

    public function removeProductFromCart(ProductDto $productDto): void
    {

    }
}