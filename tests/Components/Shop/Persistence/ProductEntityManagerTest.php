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

class ProductEntityManagerTest extends TestCase
{
    private ProductEntityManager $productEntityManager;
    private ProductMapper $productMapper;

    private UserMapper $userMapper;

    private SchemaBuilder $schemaBuilder;

    private DatabaseBuilder $databaseBuilder;

    private ProductRepository $productRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $_ENV['DATABASE'] = 'football_test';
        $ormSqlConnector = new ORMSqlConnector();
        $this->schemaBuilder = new SchemaBuilder($ormSqlConnector);
        $this->schemaBuilder->createSchema();
        $testData = [
            'userId' => 1,
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];
        $this->userMapper = new UserMapper();
        $userDTO = $this->userMapper->createDTO($testData);

        $this->productRepository = new ProductRepository($ormSqlConnector);
        $this->databaseBuilder = new DatabaseBuilder($ormSqlConnector);
        $this->databaseBuilder->loadData($userDTO);
        $this->productMapper = new ProductMapper();
        $this->productEntityManager = new ProductEntityManager($ormSqlConnector);
    }

    protected function tearDown(): void
    {
        $this->schemaBuilder->dropSchema();
        unset($_ENV['DATABASE']);
        parent::tearDown();
    }

    public function testSaveProductEntity(): void
    {
        $productDto = $this->productMapper->createProductDto('jersey', 'bayern jersey', 'imageLink', 'L', 3);
        $productDto->price = 9.99;

        $userDto = $this->userMapper->UserDTOWithOnlyUserId(1);
        $this->productEntityManager->saveProductEntity($productDto, $userDto);
        $productEntity = $this->productRepository->getProductEntity($productDto, $userDto);
        self::assertInstanceOf(ProductEntity::class, $productEntity);
        self::assertSame(1, $productEntity->getProductId());
    }

    public function testNothing(): void
    {
        self::assertTrue(true);
    }
}
