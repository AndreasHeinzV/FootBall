<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Communication\Controller;


use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\UserRegister\Business\UserRegisterBusinessFacadeInterface;
use App\Components\UserRegister\Persistence\DTO\UserRegisterDto;
use App\Core\RedirectInterface;
use App\Core\ViewInterface;

readonly class UserRegisterController implements UserRegisterControllerInterface
{
    public function __construct(
        private UserRegisterBusinessFacadeInterface $userRegisterBusinessFacade,
        private RedirectInterface $redirect,

    ) {
    }


    public function load(ViewInterface $view): void
    {
        $errorsDTO = null;
        $userRegisterDto = null;

        if (($_SERVER['REQUEST_METHOD'] === 'POST') && $_POST['registerMe'] === 'push') {

             $userRegisterDto = new UserRegisterDto();
             $userRegisterDto->firstName = htmlspecialchars($_POST['fName'] ?? '');
             $userRegisterDto->lastName = htmlspecialchars($_POST['lName'] ?? '');
             $userRegisterDto->email = htmlspecialchars($_POST['email'] ?? '');
             $userRegisterDto->password = htmlspecialchars($_POST['password'] ?? '');

             $errorsDTO = $this->userRegisterBusinessFacade->registerUserNew($userRegisterDto);

             if(!$errorsDTO instanceof ErrorsDTO) {
                 $this->redirect->to('');
             }
        }

        $view->addParameter('errors', $errorsDTO);
        $view->setTemplate('register.twig');
        $view->addParameter('userRegisterDto', $userRegisterDto);
    }
}

