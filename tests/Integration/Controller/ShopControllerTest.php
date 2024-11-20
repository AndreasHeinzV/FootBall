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
use App\Components\Shop\Business\Model\CreateProducts;
use App\Components\Shop\Business\ProductBusinessFacade;
use App\Components\Shop\Communication\ShopController;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Tests\Fixtures\DatabaseBuilder;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class ShopControllerTest extends TestCase
{
    private SchemaBuilder $schemaBuilder;
    private ViewFaker $view;
    private ShopController $controller;
    private ORMSqlConnector $connector;


    protected function setUp(): void
    {
        parent::setUp();
        $_ENV['DATABASE'] = 'football_test';

        $connector = new OrmSqlConnector();
        $this->schemaBuilder = new SchemaBuilder($connector);
        $this->schemaBuilder->createSchema();

        $testData = [
            'userId' => 1,
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];
        $userMapper = new UserMapper();
        $userDTO = $userMapper->createDTO($testData);

        $databaseBuilder = new DatabaseBuilder($connector);
        $databaseBuilder->loadData($userDTO);
        $this->view = new ViewFaker();
        $apiRequester = new ApiRequester(
            new LeaguesMapper(),
            new CompetitionMapper(),
            new TeamMapper(),
            new PlayerMapper()
        );
        $apiRequesterFacade = new ApiRequesterFacade($apiRequester);
        $createProducts = new CreateProducts($apiRequesterFacade);
        $productBusinessFacade = new ProductBusinessFacade($createProducts);
        $this->controller = new ShopController($productBusinessFacade);
    }

    protected function tearDown(): void
    {
        $this->schemaBuilder->dropSchema();
        unset($_GET);

        parent::tearDown();
    }

    public function testShopController(): void
    {
        $_GET['teamName'] = 'test';
        $_GET['page'] = 'shop';
        $_GET['teamId'] = '5';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->controller->load($this->view);
        $template = $this->view->getTemplate();
        $parameters = $this->view->getParameters();

        self::assertSame('shop.twig', $template);
        self::assertSame('test', $parameters['teamName']);
        self::assertNotEmpty($parameters);
    }
}