<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Api\Business\ApiRequesterFacade;
use App\Components\Api\Business\Model\ApiRequester;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SchemaBuilder;
use App\Components\Football\Mapper\CompetitionMapper;
use App\Components\Football\Mapper\LeaguesMapper;
use App\Components\Football\Mapper\PlayerMapper;
use App\Components\Football\Mapper\TeamMapper;
use App\Components\Shop\Business\Model\CalculatePrice;
use App\Components\Shop\Business\Model\CreateProducts;
use App\Components\Shop\Business\Model\ProductManager;
use App\Components\Shop\Business\ProductBusinessFacade;
use App\Components\Shop\Communication\CartController;
use App\Components\Shop\Persistence\DTOs\ProductDto;
use App\Components\Shop\Persistence\Mapper\ProductMapper;
use App\Components\Shop\Persistence\ProductEntityManager;
use App\Components\Shop\Persistence\ProductRepository;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Core\SessionHandler;
use App\Tests\Fixtures\DatabaseBuilder;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class CartControllerTest extends TestCase
{

    private SchemaBuilder $schemaBuilder;
    private DatabaseBuilder $databaseBuilder;

    private CartController $cartController;

    private ViewFaker $view;

    private ProductEntityManager $productEntityManager;

    private UserMapper $userMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $sqlConnector = new ORMSqlConnector();

        $this->schemaBuilder = new SchemaBuilder($sqlConnector);




        $testData = [
            'userId' => 1,
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];
        $this->userMapper = new UserMapper();
        $userDTO = $this->userMapper->createDTO($testData);

        $this->schemaBuilder->fillTables($userDTO);
        $this->view = new ViewFaker();
        $apiRequester = new ApiRequester(
            new LeaguesMapper(),
            new CompetitionMapper(),
            new TeamMapper(),
            new PlayerMapper()
        );
        $productMapper = new ProductMapper();
        $productRepository = new ProductRepository($sqlConnector, $productMapper);
        $this->productEntityManager = new ProductEntityManager($sqlConnector);
        $apiRequesterFacade = new ApiRequesterFacade($apiRequester);

        $createProducts = new CreateProducts($apiRequesterFacade, $productMapper);
        $calculatePrice = new CalculatePrice();
        $sessionHandlerMock = $this->createMock(SessionHandler::class);
        $sessionHandlerMock->method('getUserDTO')->willReturn($userDTO);
        $productManager = new ProductManager(
            $sessionHandlerMock,
            $productRepository,
            $this->productEntityManager
        );
        $productBusinessFacade = new ProductBusinessFacade(
            $createProducts,
            $calculatePrice,
            $productMapper,
            $productManager,
            $productRepository
        );

        $this->cartController = new CartController($sessionHandlerMock, $productBusinessFacade);
    }

    protected function tearDown(): void
    {
        $this->schemaBuilder->clearDatabase();
        unset($_POST, $_GET);
        parent::tearDown();
    }

    public function testEmptyCart(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->cartController->load($this->view);

        $template = $this->view->getTemplate();
        $params = $this->view->getParameters();


        self::assertNotEmpty($params);
    }


    public function testCartWithOneEntry(): void
    {
        $productDto = new ProductDto("messi jersey", 'Barca', "link", "jersey", "L", 9.99, "link", 1);

        $this->productEntityManager->saveProductEntity($productDto, (new UserMapper())->UserDTOWithOnlyUserId(1));

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->cartController->load($this->view);

        $template = $this->view->getTemplate();
        $params = $this->view->getParameters();

        $products = $params['products'];

        self::assertSame("cart.twig", $template);
        self::assertNotEmpty($params);
        self::assertSame($products[0]->name, "messi jersey");
    }


    public function testIncreaseProductAmount(): void
    {
        $productDto = new ProductDto("messi jersey", 'Barca', "link", "jersey", "L", 9.99, "link", 1);
        $this->productEntityManager->saveProductEntity($productDto, (new UserMapper())->UserDTOWithOnlyUserId(1));
        $amount = $productDto->amount;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['productName'] = 'messi jersey';
        $_POST['changeAmount'] = 'increase';
        $this->cartController->load($this->view);
        $template = $this->view->getTemplate();
        $params = $this->view->getParameters();

        self::assertNotSame($amount, $params['products'][0]->amount);
    }

    public function testDecreaseProductAmount(): void
    {
        $productDto = new ProductDto("messi jersey", 'Barca', "link", "jersey", "L", 9.99, "link", 2);
        $this->productEntityManager->saveProductEntity($productDto, (new UserMapper())->UserDTOWithOnlyUserId(1));
        $amount = $productDto->amount;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['productName'] = 'messi jersey';
        $_POST['changeAmount'] = 'decrease';
        $this->cartController->load($this->view);
        $template = $this->view->getTemplate();
        $params = $this->view->getParameters();

        self::assertNotSame($amount, $params['products'][0]->amount);
        self::assertSame(1, $params['products'][0]->amount);
    }

    public function testDecreaseProductAmountToZero(): void
    {
        $productDto = new ProductDto("messi jersey", 'Barca', "link", "jersey", "L", 9.99, "link", 1);
        $this->productEntityManager->saveProductEntity($productDto, (new UserMapper())->UserDTOWithOnlyUserId(1));
        $amount = $productDto->amount;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['productName'] = 'messi jersey';
        $_POST['changeAmount'] = 'decrease';
        $this->cartController->load($this->view);
        $template = $this->view->getTemplate();
        $params = $this->view->getParameters();
        self::assertSame(1, $amount);
        self::assertEmpty($params['products']);
    }

    public function testDeleteProduct(): void{
        $productDto = new ProductDto("messi jersey", 'Barca', "link", "jersey", "L", 9.99, "link", 1);
        $this->productEntityManager->saveProductEntity($productDto, (new UserMapper())->UserDTOWithOnlyUserId(1));

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['productName'] = 'messi jersey';
        $_POST['deleteProduct'] = 'true';
        $this->cartController->load($this->view);
        $params = $this->view->getParameters();

        self::assertEmpty($params['products']);

    }
}