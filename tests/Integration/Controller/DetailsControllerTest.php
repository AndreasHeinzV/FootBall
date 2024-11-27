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
use App\Components\Shop\Communication\DetailsController;
use App\Components\Shop\Persistence\Mapper\ProductMapper;
use App\Components\Shop\Persistence\ProductEntityManager;
use App\Components\Shop\Persistence\ProductRepository;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Core\SessionHandler;
use App\Tests\Fixtures\DatabaseBuilder;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class DetailsControllerTest extends TestCase
{

    private DetailsController $controller;

    private ViewFaker $view;

    private SchemaBuilder $schemaBuilder;
    private UserMapper $userMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $_ENV['DATABASE'] = 'football_test';

        $this->view = new ViewFaker();
        $apiRequester = new ApiRequester(
            new LeaguesMapper(),
            new CompetitionMapper(),
            new TeamMapper(),
            new PlayerMapper()
        );
        $apiRequesterFacade = new ApiRequesterFacade($apiRequester);
        $productMapper = new ProductMapper();
        $createProducts = new CreateProducts($apiRequesterFacade, $productMapper);
        $calculatePrice = new CalculatePrice();
        $ormSqlConnector = new ORMSqlConnector();
        $productRepository = new ProductRepository($ormSqlConnector, $productMapper);
        $productEntityManager = new ProductEntityManager($ormSqlConnector);

        $testData = [
            'userId' => 1,
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];
        $this->userMapper = new UserMapper();
        $userDTO = $this->userMapper->createDTO($testData);


        $sessionHandlerMock = $this->createMock(SessionHandler::class);
        $sessionHandlerMock->method('getUserDTO')->willReturn($userDTO);
        $productManager = new ProductManager(
            $sessionHandlerMock,
            $productRepository,
            $productEntityManager
        );
        $productBusinessFacade = new ProductBusinessFacade(
            $createProducts,
            $calculatePrice,
            $productMapper,
            $productManager,
            $productRepository
        );


        $this->controller = new DetailsController($productBusinessFacade);
        $this->schemaBuilder = new SchemaBuilder($ormSqlConnector);
        $this->schemaBuilder->createSchema();

        (new DatabaseBuilder($ormSqlConnector))->loadData($userDTO);
    }


    protected function tearDown(): void
    {
        $this->schemaBuilder->dropSchema();
        unset($this->controller, $_GET, $_POST);


        parent::tearDown();
    }

    public function testDetailsLoad(): void
    {
        $_GET['category'] = 'soccerJersey';
        $_GET['name'] = 'Jersey';
        $_GET['imageLink'] = "link";
        $_SERVER['REQUEST_METHOD'] = 'GET';


        $this->controller->load($this->view);

        $template = $this->view->getTemplate();
        $result = $this->view->getParameters();
        $product = $result['productDto'];
        self::assertSame('details.twig', $template);
        self::assertNotEmpty($result);
        self::assertSame('Jersey', $product['name']);
    }

    public function testDetailsPost(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['calculatePriceButton'] = 'submit';
        $_POST['category'] = 'soccerJersey';
        $_POST['name'] = 'Michael';
        $_POST['imageLink'] = "testlink";
        $_POST['size'] = "L";
        $this->controller->load($this->view);

        $template = $this->view->getTemplate();
        $result = $this->view->getParameters();
        $productDto = $result['productDto'];

        self::assertSame('details.twig', $template);
        self::assertNotEmpty($result);
        self::assertSame('Michael', $productDto['name']);
        self::assertSame(24.99, $productDto['price']);
    }


    public function testSaveDetailsToCart(): void
    {

        $_POST['addToCartButton'] = 'addToCart';
        $_POST['category'] = 'soccerJersey';
        $_POST['name'] = 'MichaelJersey';
        $_POST['imageLink'] = "testLink";
        $_POST['size'] = "L";
        $_POST['price'] = 24.99;
        $this->controller->load($this->view);
        $template = $this->view->getTemplate();
        $result = $this->view->getParameters();
        $productDto = $result['productDto'];
        self::assertSame('details.twig', $template);
        self::assertNotEmpty($result);
    }


    /*
        public function testSaveDetailsToCartPost(): void
        {
            $_SERVER['REQUEST_METHOD'] = 'POST';
            $_POST['addToCartButton'] = 'addToCart';
            $_POST['category'] = 'soccerJersey';
            $_POST['name'] = 'MichaelJersey';
            $_POST['imageLink'] = "testLink";
            $_POST['size'] = "L";
            $_POST['price'] = 24.99;
            $this->controller->load($this->view);
            $template = $this->view->getTemplate();
            $result = $this->view->getParameters();
            $productDto = $result['productDto'];

            self::assertSame('details.twig', $template);
            self::assertNotEmpty($result);
        }
    */
}