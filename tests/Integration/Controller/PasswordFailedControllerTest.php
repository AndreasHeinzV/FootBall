<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Database\Persistence\Mapper\UserEntityMapper;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SchemaBuilder;
use App\Components\Database\Persistence\SqlConnector;
use App\Components\PasswordReset\Business\Model\PasswordFailed\ActionIdGenerator;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailBuilder;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailCoordinator;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailDispatcher;
use App\Components\PasswordReset\Business\Model\PasswordFailed\EmailValidationPasswordFailed;
use App\Components\PasswordReset\Business\Model\PasswordReset\AccessManager;
use App\Components\PasswordReset\Business\Model\PasswordReset\ResetCoordinator;
use App\Components\PasswordReset\Business\Model\PasswordReset\ResetErrorDtoProvider;
use App\Components\PasswordReset\Business\Model\PasswordReset\TimeManager;
use App\Components\PasswordReset\Business\Model\PasswordReset\ValidateResetErrors;
use App\Components\PasswordReset\Business\Model\PasswordReset\Validation\ValidateDuplicatePassword;
use App\Components\PasswordReset\Business\Model\PasswordReset\Validation\ValidateFirstPassword;
use App\Components\PasswordReset\Business\Model\PasswordReset\Validation\ValidateSecondPassword;
use App\Components\PasswordReset\Business\PasswordResetBusinessFacade;
use App\Components\PasswordReset\Communication\Controller\PasswordFailedController;
use App\Components\PasswordReset\Persistence\EntityManager\UserPasswordResetEntityManager;
use App\Components\PasswordReset\Persistence\Mapper\ActionMapper;
use App\Components\PasswordReset\Persistence\Repository\UserPasswordResetRepository;
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
    private ORMSqlConnector $sqlConnector;

    private SchemaBuilder $schemaBuilder;

    protected function setUp(): void
    {
        parent::setUp();

        $_ENV['DATABASE'] = 'football_test';
        $this->view = new ViewFaker();
        $userEntityMapper = new UserEntityMapper();
        $this->sqlConnector = new ORMSqlConnector();
        $userRepository = new UserRepository($this->sqlConnector, $userEntityMapper);
        $userEntityManager = new UserEntityManager($this->sqlConnector);
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

        $this->schemaBuilder = new SchemaBuilder($this->sqlConnector);
        $this->schemaBuilder->createSchema();
        $this->databaseBuilder = new DatabaseBuilder($this->sqlConnector);

      //  $this->databaseBuilder->loadData($userDTO);
        $userEntityManager->saveUser($userDTO);


        $emailValidation = new EmailValidationPasswordFailed();
        $emailBuilder = new EmailBuilder();
        $emailDispatcher = new EmailDispatcher(new PHPMailer());
        $timeManager = new TimeManager();
        $actionIdGenerator = new ActionIdGenerator();
        $userPasswordResetEntityManager = new UserPasswordResetEntityManager($this->sqlConnector);


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
        $actionMapper = new ActionMapper();
        $userPasswordResetRepository = new UserPasswordResetRepository($this->sqlConnector, $actionMapper);


        $validateFirstPassword = new ValidateFirstPassword();
        $validateSecondPassword = new ValidateSecondPassword();
        $validateDuplicatePassword = new ValidateDuplicatePassword();

        $validateResetErrors = new ValidateResetErrors(
            $validateFirstPassword,
            $validateSecondPassword,
            $validateDuplicatePassword
        );
        $resetErrorDtoProvider = new ResetErrorDtoProvider($validateResetErrors);
        $resetCoordinator = new ResetCoordinator(
            $resetErrorDtoProvider,
            $userPasswordResetRepository,
            $userPasswordResetEntityManager,
            $userBusinessFacade,
            $userMapper
        );


        $accessManager = new AccessManager($userPasswordResetRepository, $actionMapper, $timeManager);
        $passwordFailedBusinessFacade = new PasswordResetBusinessFacade(
            $emailCoordinator,
            $resetCoordinator,
            $accessManager
        );
        $this->controller = new PasswordFailedController($passwordFailedBusinessFacade, $redirect);
    }

    protected function tearDown(): void
    {
        $this->schemaBuilder->dropSchema();
        unset($_ENV);

        parent::tearDown();
    }

    public function testSendMail(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['password-failed'] = 'push';
        $_POST['email'] = 'push@example.com';

        $this->controller->load($this->view);
        $parameters = $this->view->getParameters();
        $output = $parameters['passwordStatus'];
        self::assertSame('password-failed.twig', $this->view->getTemplate());
        self::assertNotEmpty($parameters);
        self::assertTrue($output);
    }

    public function testSendMailFailed(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['password-failed'] = 'push';
        $_POST['email'] = 'pull@example.com';

        $this->controller->load($this->view);
        $parameters = $this->view->getParameters();
        self::assertSame('password-failed.twig', $this->view->getTemplate());
        self::assertNotEmpty($parameters);
        $output = $parameters['passwordStatus'];
        self::assertFalse($output);
    }
}