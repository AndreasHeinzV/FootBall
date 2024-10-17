<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\User\Persistence\Mapper\ErrorMapper;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserLogin\Communication\Controller\LoginController;
use App\Components\Validation\Validation;
use App\Core\SessionHandler;
use App\Tests\Fixtures\RedirectSpy;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{

    public Validation $validation;
    public ViewFaker $viewFaker;
    public UserRepository $userRepository;

    public UserMapper $userMapper;

    public ErrorMapper $errorMapper;
    public SessionHandler $sessionHandler;
    public RedirectSpy $redirectSpy;

    protected function setUp(): void
    {
        parent::setUp();
        $_ENV['test'] = 1;
        $this->viewFaker = new ViewFaker();
        $this->userRepository = new UserRepository();
        $this->validation = new Validation();
        $this->userMapper = new UserMapper();
        $this->errorMapper = new ErrorMapper();
        $this->redirectSpy = new RedirectSpy();
        $this->sessionHandler = new SessionHandler($this->userMapper);
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

    protected function tearDown(): void
    {
        unset(
            $_ENV['test'], $_SERVER['REQUEST_METHOD'],
            $_POST['loginButton'],
            $_POST['email'],
            $_POST['password'],
            $this->validation,
            $this->viewFaker,
            $this->userRepository,
            $this->userMapper,
            $this->errorMapper,
            $this->sessionHandler,
            $this->redirectSpy
        );
        parent::tearDown();
    }

    public function testLoginFail(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['loginButton'] = 'login';
        $_POST['email'] = 'gewg@g.com';
        $_POST['password'] = 'wgw';

        $loginController = new LoginController(
            $this->userRepository,
            $this->userMapper,
            $this->validation,
            $this->sessionHandler,
            $this->redirectSpy
        );

        $loginController->load($this->viewFaker);
        $loginStatus = $this->sessionHandler->getStatus();
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

        $loginController = new LoginController(
            $this->userRepository,
            $this->userMapper,
            $this->validation,
            $this->sessionHandler,
            $this->redirectSpy
        );

        $loginController->load($this->viewFaker);
        $parameters = $this->viewFaker->getParameters();
        $template = $this->viewFaker->getTemplate();
        $errors = $this->errorMapper->createErrorDTO($parameters['errors']);
        $userDto = $this->userMapper->createDTO($parameters['userDto']);


        self::assertSame('login.twig', $template);
        self::assertArrayHasKey('errors', $parameters, "check for existing parameters");
        self::assertArrayHasKey('userDto', $parameters);
        self::assertTrue($this->sessionHandler->getStatus(), "expected value is true");
        self::assertSame('ilovecats@gmail.com', $userDto->email, "expected email");
    }

    public function testLoginFailEmptyPassword(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['loginButton'] = 'login';
        $_POST['email'] = 'gewg@g.com';
        $_POST['password'] = '';

        $loginController = new LoginController(
            $this->userRepository,
            $this->userMapper,
            $this->validation,
            $this->sessionHandler,
            $this->redirectSpy
        );

        $loginController->load($this->viewFaker);
        $loginStatus = $this->sessionHandler->getStatus();
        $parameters = $this->viewFaker->getParameters();

        $template = $this->viewFaker->getTemplate();
        $errors = $this->errorMapper->createErrorDTO($parameters['errors']);
        $userDto = $this->userMapper->createDTO($parameters['userDto']);


        self::assertSame('login.twig', $template);
        self::assertArrayHasKey('errors', $parameters, "check for existing parameters");
        self::assertArrayHasKey('userDto', $parameters);
        self::assertFalse($loginStatus, "expected value is false");
        self::assertSame('Password is empty.', $errors->passwordError, "expected message");
        self::assertSame('gewg@g.com', $userDto->email, "expected email");
    }
}