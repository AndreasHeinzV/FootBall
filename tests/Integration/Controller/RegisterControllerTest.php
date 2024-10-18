<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Components\User\Persistence\Mapper\ErrorMapper;
use App\Components\User\Persistence\Mapper\UserMapper;
use App\Components\User\Persistence\UserEntityManager;
use App\Components\User\Persistence\UserRepository;
use App\Components\UserLogin\Business\Model\UserLoginValidation;
use App\Components\UserRegister\Communication\Controller\RegisterController;
use App\Tests\Fixtures\RedirectSpy;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

class RegisterControllerTest extends TestCase
{
    public ViewFaker $viewFaker;
    public UserMapper $userMapper;

    public UserLoginValidation $validation;

    public ErrorMapper $errorMapper;
    public RedirectSpy $redirectSpy;

    protected function setUp(): void
    {
        $_ENV['test'] = 1;
        parent::setUp();
        $this->viewFaker = new ViewFaker();
        $this->userMapper = new UserMapper();
        $this->validation = new UserLoginValidation();
        $this->errorMapper = new ErrorMapper();
        $this->redirectSpy = new RedirectSpy();
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

        $userEntityManager = new UserEntityManager($this->validation, new UserRepository(), $this->userMapper,);
        $userRegisterController = new RegisterController(
            $userEntityManager,
            $this->validation,
            $this->userMapper,
            $this->redirectSpy
        );

        $userRegisterController->load($this->viewFaker);
        $parameters = $this->viewFaker->getParameters();

        $errorData = $parameters['errors'];
        $errorDTO = $this->errorMapper->arrayToDto($errorData);


        self::assertNotEmpty($this->viewFaker->getTemplate());
        self::assertSame('', $errorDTO->emailError);
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

        $userEntityManager = new UserEntityManager($this->validation, new UserRepository(), $this->userMapper,);
        $userRegisterController = new RegisterController(
            $userEntityManager,
            $this->validation,
            $this->userMapper,
            $this->redirectSpy
        );

        $userRegisterController->load($this->viewFaker);
        $parameters = $this->viewFaker->getParameters();

        $errorData = $parameters['errors'];
        $errorDTO = $this->errorMapper->arrayToDto($errorData);


        self::assertNotEmpty($this->viewFaker->getTemplate());
        self::assertSame('', $errorDTO->emailError);
    }
}