<?php

declare(strict_types=1);

namespace App\Tests\Integration\Controller;

use App\Controller\RegisterController;
use App\Core\Validation;
use App\Model\DTOs\ErrorsDTO;
use App\Model\Mapper\ErrorMapper;
use App\Model\Mapper\UserMapper;
use App\Model\UserEntityManager;
use App\Model\UserRepository;
use App\Tests\Fixtures\ViewFaker;
use PHPUnit\Framework\TestCase;

use PHPUnit\TextUI\XmlConfiguration\Validator;

use function PHPUnit\Framework\assertTrue;

class RegisterControllerTest extends TestCase
{
    public ViewFaker $viewFaker;
    public UserMapper $userMapper;

    public Validation $validation;

    public ErrorMapper $errorMapper;

    protected function setUp(): void
    {
        $_ENV['test'] = 1;
        parent::setUp();
        $this->viewFaker = new ViewFaker();
        $this->userMapper = new UserMapper();
        $this->validation = new Validation();
        $this->errorMapper = new ErrorMapper();
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
        $userRegisterController = new RegisterController($userEntityManager, $this->validation, $this->userMapper);

        $userRegisterController->load($this->viewFaker);
        $parameters = $this->viewFaker->getParameters();

        $errorData = $parameters['errors'];
        $errorDTO = $this->errorMapper->createErrorDTO($errorData);
        //var_export($viewFaker->getTemplate());

        //var_export($errorDTO);
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
        $userRegisterController = new RegisterController($userEntityManager, $this->validation, $this->userMapper);

        $userRegisterController->load($this->viewFaker);
        $parameters = $this->viewFaker->getParameters();

        $errorData = $parameters['errors'];
        $errorDTO = $this->errorMapper->createErrorDTO($errorData);
        //var_export($viewFaker->getTemplate());

        //var_export($errorDTO);
        self::assertNotEmpty($this->viewFaker->getTemplate());
        self::assertSame('', $errorDTO->emailError);
    }
}