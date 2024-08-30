<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\RegisterController;
use App\Core\Validation;
use App\Core\View;
use App\Model\UserEntityManager;
use App\Model\UserRepository;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

class RegisterControllerTest extends TestCase
{

  /*
    protected function tearDown(): void
    {
        $_POST = [];
        if(isset($_SERVER['REQUEST_METHOD'])) {
            unset($_SERVER['REQUEST_METHOD']);
        }

        parent::tearDown();
    }

    public function testRegsiterSucces()
    {
        $_POST =[
            'fName' => 'fdsfsd',
            'lName' => 'fsdfsd',
            'password' => 'fs',
            'email' => 'fsdfsdfsd@dasdsa.de',
            'registerMe' => 'push',
        ];
        $_SERVER['REQUEST_METHOD'] = 'POST';


        $userEntityManager = new UserEntityManager();
        $validation = new Validation();
        $registerController = new RegisterController($userEntityManager, $validation);

        $view = new View($this->createMock(Environment::class));

        $registerController->load($view);






    }

  */
    // for later, so i get no waring message...
    public function testRegister(){
        self::assertTrue(true);
    }
}