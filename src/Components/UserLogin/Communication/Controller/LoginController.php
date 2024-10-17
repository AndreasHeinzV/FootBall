<?php

declare(strict_types=1);

namespace App\Components\UserLogin\Communication\Controller;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\Mapper\UserMapperInterface;
use App\Components\Validation\ValidationInterface;
use App\Core\RedirectInterface;
use App\Core\SessionHandlerInterface;
use App\Core\ViewInterface;

class LoginController implements UserLoginControllerInterface
{
    public function __construct(
        public UserBusinessFacadeInterface $userBusinessFacade,
        public UserMapperInterface $userMapper,
        public ValidationInterface $validator,
        public SessionHandlerInterface $sessionHandler,
        public RedirectInterface $redirect,
    ) {
    }

    public function load(ViewInterface $view): void
    {
        $userDTO = new UserDTO(-1,'', '', '', '');
        $errorsDTO = new ErrorsDTO('', '', '', '');

        if (($_SERVER['REQUEST_METHOD'] === 'POST') && $_POST['loginButton'] === 'login') {


            $temp['email'] = htmlspecialchars($_POST['email'] ?? '');
            $temp['password'] = htmlspecialchars($_POST['password'] ?? '');

            $userDTO = $this->userMapper->createDTO($temp);
            $errorsDTO = $this->validator->userLoginGetErrorsDTO($userDTO);

            if ($this->validator->validateNoErrors($errorsDTO)) {
                $userDTO = $this->userBusinessFacade->getUserByMail($userDTO->email);
                $this->sessionHandler->startSession($userDTO);
                $this->redirect->to('index.php');
            }
        }

        $view->addParameter('errors', $errorsDTO);
        $view->addParameter('userDto', $userDTO);
        $view->setTemplate('login.twig');
    }
}