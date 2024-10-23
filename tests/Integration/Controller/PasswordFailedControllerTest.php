<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Database\Persistence\SqlConnector;
use App\Components\PasswordReset\Business\Model\PasswordFailed\ActionIdGenerator;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailBuilder;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailCoordinator;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailDispatcher;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailValidation;
use App\Components\PasswordReset\Business\Model\PasswordReset\ResetCoordinator;
use App\Components\PasswordReset\Business\Model\TimeManager;
use App\Components\PasswordReset\Business\PasswordResetBusinessFacade;
use App\Components\PasswordReset\Communication\Controller\PasswordFailedController;
use App\Components\PasswordReset\Persistence\EntityManager\UserPasswordResetEntityManager;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Core\Redirect;
use App\Tests\Fixtures\DatabaseBuilder;
use App\Tests\Fixtures\ViewFaker;
use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;

class PasswordFailedControllerTest extends TestCase
{

    private PasswordFailedController $controller;

    private ViewFaker $view;

    private DatabaseBuilder $databaseBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $_ENV['DATABASE'] = 'football_test';
        $this->view = new ViewFaker();

        $sqlConnector = new SqlConnector();
        $userRepository = new UserRepository($sqlConnector);
        $userEntityManager = new UserEntityManager($sqlConnector);
        $userBusinessFacade = new UserBusinessFacade($userRepository, $userEntityManager);

        $testData = [
            'userId' => 1,
            'firstName' => 'ImATestCat',
            'lastName' => 'JustusCristus',
            'email' => 'push@example.com',
            'password' => password_hash('passw0rd', PASSWORD_DEFAULT),
        ];
        $userMapper = new UserMapper();
        $userDTO = $userMapper->createDTO($testData);

        $this->databaseBuilder = new DatabaseBuilder($sqlConnector);
        $this->databaseBuilder->buildTables();
        $userEntityManager->saveUser($userDTO);


        $emailValidation = new EmailValidation();
        $emailBuilder = new EmailBuilder();
        $emailDispatcher = new EmailDispatcher(new PHPMailer());
        $timeManager = new TimeManager();
        $actionIdGenerator = new ActionIdGenerator();
        $userPasswordResetEntityManager = new UserPasswordResetEntityManager($sqlConnector);


        $emailCoordinator = new EmailCoordinator(
            $emailBuilder,
            $emailDispatcher,
            $emailValidation,
            $timeManager,
            $actionIdGenerator,
            $userPasswordResetEntityManager,
            $userBusinessFacade
        );
        $redirect = new Redirect();

        $resetCoordinator = new ResetCoordinator();
        $passwordFailedBusinessFacade = new PasswordResetBusinessFacade($emailCoordinator, $resetCoordinator);
        $this->controller = new PasswordFailedController($passwordFailedBusinessFacade, $redirect);
    }

    protected function tearDown(): void
    {
        $this->databaseBuilder->dropTables();
        unset($_ENV);

        parent::tearDown();
    }

    public function testSendMail(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['password-reset'] = 'push';
        $_POST['email'] = 'push@example.com';

        $this->controller->load($this->view);
        $parameters = $this->view->getParameters();
        $output = $parameters['status'];
        self::assertSame('password-failed.twig', $this->view->getTemplate());
        self::assertNotEmpty($parameters);
        self::assertTrue($output);
    }

    public function testSendMailFailed(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['password-reset'] = 'push';
        $_POST['email'] = 'pull@example.com';

        $this->controller->load($this->view);
        $parameters = $this->view->getParameters();
        self::assertSame('password-failed.twig', $this->view->getTemplate());
        self::assertNotEmpty($parameters);
        $output = $parameters['status'];
        self::assertFalse($output);
    }
}