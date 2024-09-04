<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\HomeController;
use App\Controller\LoginController;
use App\Core\Validation;
use App\Model\Mapper\UserMapper;
use App\Model\UserRepository;
use App\Tests\Fixtures\Container;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;
use PHPUnit\TextUI\XmlConfiguration\Validator;

class HomeControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $_ENV['test'] = 1;
        $this->path = __DIR__ . '/../../../users_test.json';
        $testData = [
            [
                'firstName' => 'ImATestCat',
                'lastName' => 'JustusCristus',
                'email' => 'dog@gmail.com',
                'password' => 'passw0rd',
            ],
            [
                'firstName' => 'andi',
                'lastName' => 'dergroße',
                'email' => 'ilovecats@gmail.com',
                'password' => '$2y$10$kBSL5Jj3hSMv24dq1zm3tuNvmfgUHYNxmGOofNoKb0WIHNDRj1Nne',
            ],
        ];
        file_put_contents($this->path, json_encode($testData));
    }

    public function testIndex(): void
    {
        $view = new ViewFaker();
        $teamController = new HomeController(Container::getRepository());
        $teamController->load($view);
        $parameters = $view->getParameters();


        self::assertArrayHasKey('leagues', $parameters, "Checking if specific parameter is set");
        $playerData = $parameters['leagues'];

        self::assertCount(13, $playerData, "Checking for league count");
        self::assertSame('Championship', $playerData[1]->name, "expected League");
    }

    public function testIndexWithLogin(): void
    {
        $view = new ViewFaker();
        $userRepository = new UserRepository();
        $userMapper = new UserMapper();
        $validation = new Validation();

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['loginButton'] = 'login';
        $_POST['email'] = 'ilovecats@gmail.com';
        $_POST['password'] = '1LoveCats!';

        $loginController = new LoginController($userRepository, $userMapper, $validation);
        $loginController->load($view);


        $teamController = new HomeController(Container::getRepository());
        $teamController->load($view);
        $parameters = $view->getParameters();
        $loginStatus = $parameters['status'];
       // var_dump($parameters);
        self::assertTrue($loginStatus);
    }
}