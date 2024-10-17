<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Football\Communication\Controller\HomeController;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserLogin\Communication\Controller\LoginController;
use App\Components\Validation\Validation;
use App\Core\SessionHandler;
use App\Tests\Fixtures\Container;
use App\Tests\Fixtures\RedirectSpy;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{
    public SessionHandler $sessionHandler;

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

        $mapper = new UserMapper();
        $this->sessionHandler = new SessionHandler($mapper);
        parent::setUp();
    }

    protected function tearDown(): void
    {
        unset($_ENV['test'], $_SERVER['REQUEST_METHOD'], $_POST['loginButton'], $_POST['email'], $_POST['password']);
        parent::tearDown();
    }

    public function testIndex(): void
    {
        $view = new ViewFaker();
        $teamController = new HomeController(Container::getRepository(), $this->sessionHandler);
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
        $redirectSpy = new RedirectSpy();
        $sessionHandler = new SessionHandler($userMapper);
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['loginButton'] = 'login';
        $_POST['email'] = 'ilovecats@gmail.com';
        $_POST['password'] = '1LoveCats!';

        $loginController = new LoginController(
            $userRepository, $userMapper, $validation, $sessionHandler, $redirectSpy
        );

        $loginController->load($view);
        $teamController = new HomeController(Container::getRepository(), $sessionHandler);
        $teamController->load($view);
        $loginStatus = $sessionHandler->getStatus();

        self::assertTrue($loginStatus);
    }
}