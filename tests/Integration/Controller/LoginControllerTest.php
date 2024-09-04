<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\LoginController;
use App\Core\SessionHandler;
use App\Core\Validation;
use App\Model\Mapper\ErrorMapper;
use App\Model\Mapper\UserMapper;
use App\Model\UserRepository;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;
use PHPUnit\TextUI\XmlConfiguration\Validator;

class LoginControllerTest extends TestCase
{

    public Validation $validation;
    public ViewFaker $viewFaker;
    public UserRepository $userRepository;

    public UserMapper $userMapper;

    public ErrorMapper $errorMapper;
    public SessionHandler $sessionHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $_ENV['test'] = 1;
        $this->viewFaker = new ViewFaker();
        $this->userRepository = new UserRepository();
        $this->validation = new Validation();
        $this->userMapper = new UserMapper();
        $this->errorMapper = new ErrorMapper();
        $this->sessionHandler = new SessionHandler();
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
            ]
        ];
        file_put_contents($this->path, json_encode($testData));
    }

    public function testLoginFail(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['loginButton'] = 'login';
        $_POST['email'] = 'gewg@g.com';
        $_POST['password'] = 'wgw';

        $loginController = new LoginController($this->userRepository, $this->userMapper, $this->validation,$this->sessionHandler );

        $loginController->load($this->viewFaker);
        $loginStatus = $_SESSION['loginStatus'];
        $parameters = $this->viewFaker->getParameters();
        $template = $this->viewFaker->getTemplate();
        $errors = $this->errorMapper->createErrorDTO($parameters['errors']);
        $userDto = $this->userMapper->createDTO($parameters['userDto']);


        self::assertSame('login.twig', $template);
        self::assertArrayHasKey('errors', $parameters, "check for existing parameters");
        self::assertArrayHasKey('userDto', $parameters);
        self::assertFalse($loginStatus, "expected value is false");
        self::assertSame('email or password is wrong', $errors->passwordError, "expected message");
        self::assertSame('gewg@g.com', $userDto->email, "expected email");
    }

    public function testLogin(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['loginButton'] = 'login';
        $_POST['email'] = 'ilovecats@gmail.com';
        $_POST['password'] = '1LoveCats!';

        $loginController = new LoginController($this->userRepository, $this->userMapper, $this->validation, $this->sessionHandler );

        $loginController->load($this->viewFaker);
        $parameters = $this->viewFaker->getParameters();
        $template = $this->viewFaker->getTemplate();
        $errors = $this->errorMapper->createErrorDTO($parameters['errors']);
        $userDto = $this->userMapper->createDTO($parameters['userDto']);
    var_export($parameters);

        self::assertSame('login.twig', $template);
        self::assertArrayHasKey('errors', $parameters, "check for existing parameters");
        self::assertArrayHasKey('userDto', $parameters);
        self::assertTrue($parameters['status'], "expected value is true");
        self::assertSame('ilovecats@gmail.com', $userDto->email, "expected email");
    }
}