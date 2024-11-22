<?php

declare(strict_types=1);

namespace App\Components\Shop\Persistence;

use App\Components\Database\Persistence\Entity\ProductEntity;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Shop\Persistence\DTOs\ProductDto;
use App\Components\User\Persistence\DTOs\UserDTO;
use Doctrine\ORM\EntityManager;

class ProductRepository
{


    private EntityManager $entityManager;
    public function __construct(private ORMSqlConnector $ORMSqlConnector)
    {

        $this->entityManager = $this->ORMSqlConnector->getEntityManager();
    }

    public function getProductEntity(ProductDto $productDto, UserDTO $userDto): ?ProductEntity
    {
        $productEntity = $this->entityManager->getRepository(ProductEntity::class)->findOneBy(
            ['userId' => $userDto->userId, 'name' => $productDto->name]
        );
        return $productEntity ?? null;
    }

}