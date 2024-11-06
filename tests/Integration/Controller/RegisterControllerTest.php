<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Database\Business\DatabaseBusinessFacade;
use App\Components\Database\Business\Model\Fixtures;
use App\Components\Database\Persistence\SqlConnector;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\ErrorMapper;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserLogin\Business\Model\UserLoginValidation;
use App\Components\UserRegister\Business\Model\Register;
use App\Components\UserRegister\Business\Model\UserRegisterValidation;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\EmailValidation;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\FirstNameValidation;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\LastNameValidation;
use App\Components\UserRegister\Business\Model\ValidationTypesRegister\PasswordValidation;
use App\Components\UserRegister\Business\UserRegisterBusinessFacade;
use App\Components\UserRegister\Communication\Controller\UserRegisterController;
use App\Components\UserRegister\Persistence\Mapper\RegisterMapper;
use App\Tests\Fixtures\RedirectSpy;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class RegisterControllerTest extends TestCase
{
    private ViewFaker $viewFaker;

    private UserLoginValidation $validation;

    private RedirectSpy $redirectSpy;

    private UserRegisterController $registerController;

    protected function setUp(): void
    {
        $_ENV['test'] = 1;
        $_ENV['DATABASE'] = 'football_test';
        parent::setUp();
        $this->viewFaker = new ViewFaker();

        $errorMapper = new ErrorMapper();
        $redirectSpy = new RedirectSpy();

        $sqlConnector = new SqlConnector();
        $databaseBusinessFacade = new DatabaseBusinessFacade(
            new Fixtures($sqlConnector)
        );
        $databaseBusinessFacade->createUserTables();
        $userBusinessFacade = new UserBusinessFacade(
            new UserRepository($sqlConnector),
            new UserEntityManager($sqlConnector)
        );

        $userRegisterValidation = new UserRegisterValidation(
            $errorMapper,
            new FirstNameValidation(),
            new LastNameValidation(),
            new EmailValidation(),
            new PasswordValidation(),
        );

        $registerMapper = new RegisterMapper();
        $register = new Register(
            $userRegisterValidation,
            $userBusinessFacade,
            $registerMapper
        );
        $userRegisterBusinessFacade = new UserRegisterBusinessFacade($register);
        $this->registerController = new UserRegisterController($userRegisterBusinessFacade, $redirectSpy);
    }

    protected function tearDown(): void
    {
        unset($_SERVER['REQUEST_METHOD'], $_ENV, $_POST);
        parent::tearDown();
    }


    public function testRegisterUserWrongValues(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['registerMe'] = 'push';
        $_POST['fName'] = 'testName';
        $_POST['lName'] = 'catName';
        $_POST['email'] = 'gewg@g.com';
        $_POST['password'] = 'wgw';

        $this->registerController->load($this->viewFaker);
        $parameters = $this->viewFaker->getParameters();

        $errorData = $parameters['errors'];

        self::assertNotEmpty($this->viewFaker->getTemplate());
        self::assertNull($errorData['firstNameEmptyError']);
    }

    public function testRegisterUserRightValues(): void
    {
        $_ENV['test'] = 1;
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['registerMe'] = 'push';
        $_POST['fName'] = 'testName';
        $_POST['lName'] = 'catName';
        $_POST['email'] = 'catsAreCute@cat.com';
        $_POST['password'] = 'CatIsCute1!';

        $this->registerController->load($this->viewFaker);

        $parameters = $this->viewFaker->getParameters();
        self::assertNotEmpty($this->viewFaker->getTemplate());
        self::assertNull($parameters['errors']);
    }
}