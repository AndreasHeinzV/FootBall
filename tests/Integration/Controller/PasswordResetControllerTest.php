<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Database\Persistence\Mapper\UserEntityMapper;
use App\Components\Database\Persistence\ORMSqlConnector;
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
use App\Components\PasswordReset\Communication\Controller\PasswordResetController;
use App\Components\PasswordReset\Persistence\DTOs\MailDTO;
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

class PasswordResetControllerTest extends TestCase
{

    private ViewFaker $view;
    private PasswordResetController $passwordResetController;

    private DatabaseBuilder $databaseBuilder;

    private UserPasswordResetEntityManager $userPasswordResetEntityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $_ENV['DATABASE'] = 'football_test';
        $this->view = new ViewFaker();

        $sqlConnector = new SqlConnector();
        $ormSqlConnector = new ORMSqlConnector();
        $userEntityMapper = new UserEntityMapper();
        $userRepository = new UserRepository($ormSqlConnector, $userEntityMapper);
        $userEntityManager = new UserEntityManager($ormSqlConnector);
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


        $emailValidation = new EmailValidationPasswordFailed();
        $emailBuilder = new EmailBuilder();
        $emailDispatcher = new EmailDispatcher(new PHPMailer());
        $timeManager = new TimeManager();
        $actionIdGenerator = new ActionIdGenerator();
        $this->userPasswordResetEntityManager = new UserPasswordResetEntityManager($sqlConnector);

        $emailCoordinator = new EmailCoordinator(
            $emailBuilder,
            $emailDispatcher,
            $emailValidation,
            $timeManager,
            $actionIdGenerator,
            $this->userPasswordResetEntityManager,
            $userBusinessFacade
        );
        $redirect = new Redirect();

        $validateFirstPassword = new ValidateFirstPassword();
        $validateSecondPassword = new ValidateSecondPassword();
        $validateDuplicatePassword = new ValidateDuplicatePassword();


        $validateResetErrors = new ValidateResetErrors(
            $validateFirstPassword,
            $validateSecondPassword,
            $validateDuplicatePassword
        );
        $resetErrorDtoProvider = new ResetErrorDtoProvider($validateResetErrors);

        $userPasswordResetRepository = new UserPasswordResetRepository($sqlConnector);
        $resetCoordinator = new ResetCoordinator(
            $resetErrorDtoProvider,
            $userPasswordResetRepository,
            $this->userPasswordResetEntityManager,
            $userBusinessFacade,
            $userMapper
        );
        $actionMapper = new ActionMapper();
        $accessManager = new AccessManager($userPasswordResetRepository, $actionMapper, $timeManager);
        $passwordFailedBusinessFacade = new PasswordResetBusinessFacade(
            $emailCoordinator,
            $resetCoordinator,
            $accessManager
        );
        //  $passwordFailedBusinessFacade->sendPasswordResetEmail('test@example.com');


        $this->passwordResetController = new PasswordResetController($passwordFailedBusinessFacade, $redirect);
    }

    protected function tearDown(): void
    {
        $this->databaseBuilder->dropTables();
        unset($this->view, $_GET, $_POST);

        parent::tearDown();
    }

    public function testResetSuccessfully(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $time = time();
        $newTime = time() - 60;
        $_GET['ts'] = $time;
        $mailerDto = new MailDTO();
        $mailerDto->timestamp = $newTime;
        $mailerDto->actionId = '1234';
        $_GET['actionId'] = '1234';

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['passwordReset'] = 'push';
        $_POST['firstPassword'] = 'ILoveCats123#';
        $_POST['secondPassword'] = 'ILoveCats123#';
        $userMapper = new UserMapper();
        $userDto = $userMapper->UserDTOWithOnlyUserId(1);

        $this->userPasswordResetEntityManager->savePasswordResetAction($userDto, $mailerDto);

        $this->passwordResetController->load($this->view);
        $parameter = $this->view->getParameters();
        $template = $this->view->getTemplate();
        self::assertTrue($parameter['resetErrorDto']);
        self::assertTrue($parameter['resetAllowed']);
        self::assertSame('password-reset.twig', $template);
    }

    public function testResetTimeoutMail(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['ts'] = time() - 60;
        $_GET['actionId'] = '123';
        $time = time() + 3;
        $twoHoursAgo = time() - (2 * 60 * 60);
        $_GET['ts'] = $time;
        $mailerDto = new MailDTO();
        $mailerDto->timestamp = $twoHoursAgo;
        $mailerDto->actionId = '1234';
        $_GET['actionId'] = '1234';

        $userMapper = new UserMapper();
        $userDto = $userMapper->UserDTOWithOnlyUserId(1);
        $this->userPasswordResetEntityManager->savePasswordResetAction($userDto, $mailerDto);


        $this->passwordResetController->load($this->view);
        $parameter = $this->view->getParameters();
        $result = $parameter['resetErrorDto'];
        $template = $this->view->getTemplate();
        self::assertNotEmpty($parameter);
        self::assertFalse($parameter['resetAllowed']);
        self::assertSame('error.twig', $template);
    }

    public function testWrongPasswordEntry(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $time = time();
        $newTime = time() - 60;
        $_GET['ts'] = $time;
        $mailerDto = new MailDTO();
        $mailerDto->timestamp = $newTime;
        $mailerDto->actionId = '1234';
        $_GET['actionId'] = '1234';

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['passwordReset'] = 'push';
        $_POST['firstPassword'] = 'ILoveCats123#';
        $_POST['secondPassword'] = 'ILoveCats123';
        $userMapper = new UserMapper();
        $userDto = $userMapper->UserDTOWithOnlyUserId(1);

        $this->userPasswordResetEntityManager->savePasswordResetAction($userDto, $mailerDto);
        $this->passwordResetController->load($this->view);
        $parameter = $this->view->getParameters();
        $result = $parameter['resetErrorDto'];
        $template = $this->view->getTemplate();

        self::assertNotNull($result['secondPWValidationError']);
        self::assertIsString($result['differentPWerror']);
        //  dump($result);
        self::assertTrue($parameter['resetAllowed']);
        self::assertSame('password-reset.twig', $template);
    }

}