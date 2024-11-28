<?php

declare(strict_types=1);

namespace App\Tests\Components\Shop\Persistence;


use App\Components\Database\Persistence\Entity\ProductEntity;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SchemaBuilder;
use App\Components\Shop\Persistence\Mapper\ProductMapper;
use App\Components\Shop\Persistence\ProductEntityManager;
use App\Components\Shop\Persistence\ProductRepository;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\Mapper\UserMapperInterface;
use App\Tests\Fixtures\DatabaseBuilder;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertInstanceOf;

class ProductEntityManagerTest extends TestCase
{
    private ProductEntityManager $productEntityManager;
    private ProductMapper $productMapper;

    private UserMapper $userMapper;

    private SchemaBuilder $schemaBuilder;

    private ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $ormSqlConnector = new ORMSqlConnector();
        $this->schemaBuilder = new SchemaBuilder($ormSqlConnector);

        $testData = [
            'userId' => 1,
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];
        $this->userMapper = new UserMapper();
        $userDTO = $this->userMapper->createDTO($testData);
        $this->productMapper = new ProductMapper();
        $this->productRepository = new ProductRepository($ormSqlConnector, $this->productMapper);
        $this->schemaBuilder->fillTables($userDTO);


        $this->productEntityManager = new ProductEntityManager($ormSqlConnector);
    }

    protected function tearDown(): void
    {
        $this->schemaBuilder->clearDatabase();
        parent::tearDown();
    }

    public function testSaveProductEntity(): void
    {
        $productDto = $this->productMapper->createProductDto(
            'jersey',
            'team',
            'bayern jersey',
            'imageLink',
            'L',
            3,
            9.99
        );

        $userDto = $this->userMapper->UserDTOWithOnlyUserId(1);
        $this->productEntityManager->saveProductEntity($productDto, $userDto);
        $productEntity = $this->productRepository->getProductEntity($productDto, $userDto);
        self::assertInstanceOf(ProductEntity::class, $productEntity);
        self::assertSame(1, $productEntity->getProductId());
    }


    public function testUpdateProductEntityAmount(): void
    {
        $productDto = $this->productMapper->createProductDto(
            'jersey',
            'team',
            'bayern jersey',
            'imageLink',
            'L',
            3,
            9.99
        );

        $userDto = $this->userMapper->UserDTOWithOnlyUserId(1);
        $this->productEntityManager->saveProductEntity($productDto, $userDto);
        $productEntityOld = $this->productRepository->getProductEntity($productDto, $userDto);
        $oldAmount = $productEntityOld->getAmount();

        $this->productEntityManager->manipulateProductEntityAmount($productEntityOld, 5);
        $productEntityNew = $this->productRepository->getProductEntity($productDto, $userDto);
        self::assertInstanceOf(ProductEntity::class, $productEntityNew);
        self::assertSame(8, $productEntityNew->getAmount());
        self::assertNotSame($oldAmount, $productEntityNew->getAmount());
    }


    public function testDeleteProductEntity(): void
    {
        $productDto = $this->productMapper->createProductDto(
            'jersey',
            'team',
            'bayern jersey',
            'imageLink',
            'L',
            3,
            9.99
        );

        $userDto = $this->userMapper->UserDTOWithOnlyUserId(1);
        $this->productEntityManager->saveProductEntity($productDto, $userDto);
        $productEntityOld = $this->productRepository->getProductEntity($productDto, $userDto);


        $userDto = (new UserMapper())->UserDTOWithOnlyUserId(1);
        $this->productEntityManager->deleteProductEntity($userDto, 1);
        $deletedEntity = $this->productRepository->getProductEntity($productDto, $userDto);

        self::assertInstanceOf(ProductEntity::class, $productEntityOld);
        self::assertNull($deletedEntity);
    }

    public function testNothing(): void
    {
        self::assertTrue(true);
    }


}
