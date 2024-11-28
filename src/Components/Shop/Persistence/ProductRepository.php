<?php

declare(strict_types=1);

namespace App\Components\Shop\Persistence;

use App\Components\Database\Persistence\Entity\ProductEntity;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Shop\Persistence\DTOs\ProductDto;
use App\Components\Shop\Persistence\Mapper\ProductMapper;
use App\Components\User\Persistence\DTOs\UserDTO;
use Doctrine\ORM\EntityManager;

class ProductRepository
{


    private EntityManager $entityManager;

    public function __construct(private ORMSqlConnector $ORMSqlConnector, private ProductMapper $productMapper)
    {
        $this->entityManager = $this->ORMSqlConnector->getEntityManager();
    }

    public function getProductEntity(ProductDto $productDto, UserDTO $userDto): ?ProductEntity
    {
        $productEntity = $this->entityManager->getRepository(ProductEntity::class)->findOneBy(
            ['userIdPd' => $userDto->userId, 'name' => $productDto->name]
        );
        return $productEntity ?? null;
    }
    public function getProductEntityByName(UserDTO $userDto, string $name): ?ProductEntity
    {
        $productEntity = $this->entityManager->getRepository(ProductEntity::class)->findOneBy(
            ['userIdPd' => $userDto->userId, 'name' => $name]
        );
        return $productEntity ?? null;
    }
    public function getProductEntities(UserDTO $userDto): array
    {
        $productEntities = $this->entityManager->getRepository(ProductEntity::class)->findBy(
            ["userIdPd" => $userDto->userId]
        );

        if (empty($productEntities)) {
            return [];
        }
        $productDtoEntities = [];
        foreach ($productEntities as $productEntity) {
            $productDtoEntities[] = $this->productMapper->createProductDto(
                $productEntity->getCategory(),
                $productEntity->getTeamName(),
                $productEntity->getName(),
                $productEntity->getImageLink(),
                $productEntity->getSize(),
                $productEntity->getAmount(),
                $productEntity->getPrice()
            );
        }
        return $productDtoEntities;
    }

}