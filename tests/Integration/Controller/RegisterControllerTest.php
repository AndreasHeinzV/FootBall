<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\Database\Business\DatabaseBusinessFacade;
use App\Components\Database\Business\Model\Fixtures;
use App\Components\Database\Persistence\Mapper\UserEntityMapper;
use App\Components\Database\Persistence\ORMSqlConnector;
use App\Components\Database\Persistence\SchemaBuilder;
use App\Components\Database\Persistence\SqlConnector;
use App\Components\User\Business\UserBusinessFacade;
use App\Components\User\Persistence\Mapper\ErrorMapper;
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

    private UserRegisterController $registerController;

    private SchemaBuilder $schemaBuilder;

    protected function setUp(): void
    {

        parent::setUp();
        $this->viewFaker = new ViewFaker();

        $errorMapper = new ErrorMapper();
        $redirectSpy = new RedirectSpy();
        $userEntityMapper = new UserEntityMapper();
        $ORMSqlConnector = new ORMSqlConnector();

        $this->schemaBuilder = new SchemaBuilder($ORMSqlConnector);

        $userBusinessFacade = new UserBusinessFacade(
            new UserRepository($ORMSqlConnector, $userEntityMapper),
            new UserEntityManager($ORMSqlConnector)
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
        unset($_SERVER['REQUEST_METHOD'], $_POST);
        $this->schemaBuilder->clearDatabase();
        parent::tearDown();
    }


    public function testRegisterUserWrongValues(): void
    {
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