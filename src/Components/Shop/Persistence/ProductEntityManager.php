<?php

declare(strict_types=1);

namespace App\Components\Shop\Persistence;

use App\Components\Database\Persistence\Entity\ProductEntity;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Shop\Persistence\DTOs\ProductDto;
use App\Components\User\Persistence\DTOs\UserDTO;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ProductEntityManager
{

    private EntityManager $entityManager;

    public function __construct(private ORMSqlConnector $connector)
    {
        $this->entityManager = $this->connector->getEntityManager();
    }

    public function saveProductEntity(ProductDto $productDto, UserDTO $userDTO): void
    {
        $productEntity = new ProductEntity();
        $productEntity->setName($productDto->name);
        $productEntity->setPrice($productDto->price);
        $productEntity->setAmount($productDto->amount);
        $productEntity->setSize($productDto->size);
        $productEntity->setUserId($userDTO->userId);

        $this->entityManager->persist($productEntity);
        $this->entityManager->flush();
    }

    public function updateProductEntity(ProductEntity $productEntity, int $amount): void
    {
        $productEntity->setAmount($amount);
        $this->entityManager->persist($productEntity);
        $this->entityManager->flush();
    }

    public function deleteProductEntity(UserDTO $userDTO, string $id): void
    {
        $favoriteProductEntity = $this->entityManager->getRepository(ProductEntity::class)->findOneBy(
            ['id' => $id, 'userId' => $userDTO->userId]
        );
        if ($favoriteProductEntity !== null) {
            $this->entityManager->remove($favoriteProductEntity);
            $this->entityManager->flush();
        }
    }
}