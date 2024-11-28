<?php

declare(strict_types=1);

namespace App\Tests\Components\Shop\Business;

use App\Components\Database\Persistence\Entity\ProductEntity;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SchemaBuilder;
use App\Components\Shop\Business\Model\ProductManager;
use App\Components\Shop\Persistence\DTOs\ProductDto;
use App\Components\Shop\Persistence\Mapper\ProductMapper;
use App\Components\Shop\Persistence\ProductEntityManager;
use App\Components\Shop\Persistence\ProductRepository;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Core\SessionHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductManagerTest extends TestCase
{

    private ProductManager $productManager;

    private SchemaBuilder $schemaBuilder;

    private ProductRepository $productRepository;

    private MockObject $sessionHandler;

    protected function setUp(): void
    {

        $this->sessionHandler = $this->createMock(SessionHandler::class);

        $sqlConnector = new ORMSqlConnector();
        $this->schemaBuilder = new SchemaBuilder($sqlConnector);

        $testData = [
            'userId' => 1,
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];
        $userMapper = new UserMapper();
        $userDTO = $userMapper->createDTO($testData);
        $this->sessionHandler->method('getUserDto')->willReturn($userDTO);
        $this->schemaBuilder->fillTables($userDTO);

        $productMapper = new ProductMapper();
        $this->productRepository = new ProductRepository($sqlConnector, $productMapper);
        $productEntityManager = new ProductEntityManager($sqlConnector);

        $this->productManager = new ProductManager($this->sessionHandler, $this->productRepository, $productEntityManager);
    }

    protected function tearDown(): void
    {
        $this->schemaBuilder->clearDatabase();
    }

    public function testAddProductToExistingProduct(): void
    {
        $productDto = new ProductDto('name', 'bayern', 'link', 'soccerJersey', 'XL', 39.99, 'link2', 2);
        $this->productManager->addProductToCart($productDto);
        $this->productManager->addProductToCart($productDto);

        $productEntity = $this->productRepository->getProductEntityByName(
            (new UserMapper())->UserDTOWithOnlyUserId(1),
            'name'
        );
        self::assertInstanceOf(ProductEntity::class, $productEntity);
    }

}