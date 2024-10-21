<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Database\Business\DatabaseBusinessFacade;
use App\Components\Database\Business\Model\Fixtures;
use App\Components\Database\Persistence\SqlConnector;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\Mapper\ErrorMapper;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserLogin\Business\Model\Login;
use App\Components\UserLogin\Business\Model\UserLoginValidation;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\EmailLoginValidation;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\PasswordLoginValidation;
use App\Components\UserLogin\Business\Model\ValidationTypesLogin\UserAuthentication;
use App\Components\UserLogin\Business\UserLoginBusinessFacade;
use App\Components\UserLogin\Communication\Controller\LoginController;
use App\Core\SessionHandler;
use App\Tests\Fixtures\RedirectSpy;
use App\Tests\Fixtures\ViewFaker;
use Couchbase\User;
use PHPUnit\Framework\TestCase;

class LoginControllerTest extends TestCase
{

    private ViewFaker $viewFaker;
    private LoginController $loginController;

    private SessionHandler $sessionHandler;

    private ErrorMapper $errorMapper;

    private UserMapper $userMapper;

    private DatabaseBusinessFacade $databaseBusinessFacade;
    protected function setUp(): void
    {
        parent::setUp();
        $_ENV['test'] = 1;
        $_ENV['DATABASE'] = 'football_test';
        $this->viewFaker = new ViewFaker();
        $this->userMapper = new UserMapper();
        $this->errorMapper = new ErrorMapper();
        $redirectSpy = new RedirectSpy();

        $sqlConnector = new SqlConnector();
        $this->databaseBusinessFacade = new DatabaseBusinessFacade(
            new Fixtures($sqlConnector)
        );
          $this->databaseBusinessFacade->createUserTables();
        $userBusinessFacade = new UserBusinessFacade(
            new UserRepository($sqlConnector),
            new UserEntityManager($sqlConnector)
        );

        $userAuthentication = new UserAuthentication($userBusinessFacade);
        $emailLoginValidation = new EmailLoginValidation();
        $passwordLoginValidation = new PasswordLoginValidation($userAuthentication);
        $userLoginValidation = new UserLoginValidation(
            $this->errorMapper,
            $emailLoginValidation,
            $passwordLoginValidation
        );
        $this->sessionHandler = new SessionHandler($this->userMapper);
        $login = new Login(
            $userLoginValidation,
            $userBusinessFacade,
            $this->sessionHandler
        );


        $userLoginBusinessFacade = new UserLoginBusinessFacade($login);
        $this->loginController = new LoginController(
            $userLoginBusinessFacade,
            $redirectSpy,
        );

        $testData = [
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'dog@gmail.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];

        /*
         * [
                 'firstName' => 'andi',
                 'lastName' => 'Baumgard',
                 'email' => 'ilovecats@gmail.com',
                 'password' => '$2y$10$kBSL5Jj3hSMv24dq1zm3tuNvmfgUHYNxmGOofNoKb0WIHNDRj1Nne',
             ],
         */
        $userMapper = new UserMapper();
        $userEntityManager = new UserEntityManager($sqlConnector);
        $userEntityManager->saveUser($userMapper->createDTO($testData));

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
        $this->databaseBusinessFacade->dropUserTables();
        parent::tearDown();
    }

    public function testLoginFail(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['loginButton'] = 'login';
        $_POST['email'] = 'gewg@g.com';
        $_POST['password'] = 'wgw';


        $this->loginController->load($this->viewFaker);
        $loginStatus = $this->sessionHandler->getStatus();
        $parameters = $this->viewFaker->getParameters();

        $template = $this->viewFaker->getTemplate();
        $errors = $this->errorMapper->arrayToDto($parameters['errors']);
        $userDto = $this->userMapper->createDTO($parameters['userLoginDto']);


        self::assertSame('login.twig', $template);
        self::assertArrayHasKey('errors', $parameters, "check for existing parameters");
        self::assertArrayHasKey('userLoginDto', $parameters);
        self::assertFalse($loginStatus, "expected value is false");
        self::assertSame('email or password is wrong', $errors->passwordError, "expected message");
        self::assertSame('gewg@g.com', $userDto->email, "expected email");
    }

    public function testLoginRightValues(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['loginButton'] = 'login';
        $_POST['email'] = 'dog@gmail.com';
        $_POST['password'] = 'passw0rd';



        $this->loginController->load($this->viewFaker);
        $parameters = $this->viewFaker->getParameters();
        $template = $this->viewFaker->getTemplate();
     //   $errors = $this->errorMapper->arrayToDto($parameters['errors']);
        $userDto = $this->userMapper->createDTO($parameters['userLoginDto']);


        self::assertSame('login.twig', $template);
        self::assertArrayHasKey('errors', $parameters, "check for existing parameters");
        self::assertArrayHasKey('userLoginDto', $parameters);
        self::assertTrue($this->sessionHandler->getStatus(), "expected value is true");
        self::assertSame('dog@gmail.com', $userDto->email, "expected email");
    }

    public function testLoginFailEmptyPassword(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['loginButton'] = 'login';
        $_POST['email'] = 'gewg@g.com';
        $_POST['password'] = '';


        $this->loginController->load($this->viewFaker);
        $loginStatus = $this->sessionHandler->getStatus();
        $parameters = $this->viewFaker->getParameters();

        $template = $this->viewFaker->getTemplate();
        $errors = $this->errorMapper->arrayToDto($parameters['errors']);
        $userDto = $this->userMapper->createDTO($parameters['userLoginDto']);


        self::assertSame('login.twig', $template);
        self::assertArrayHasKey('errors', $parameters, "check for existing parameters");
        self::assertArrayHasKey('userLoginDto', $parameters);
        self::assertFalse($loginStatus, "expected value is false");
        self::assertSame('Password is empty.', $errors->passwordError, "expected message");
        self::assertSame('gewg@g.com', $userDto->email, "expected email");
    }
}