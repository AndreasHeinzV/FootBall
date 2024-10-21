<?php

declare(strict_types=1);

namespace App\Components\UserLogin\Communication\Controller;

use App\Components\User\Business\UserBusinessFacadeInterface;
use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\Mapper\UserMapperInterface;
use App\Components\UserLogin\Business\Model\UserLoginValidationInterface;
use App\Components\UserLogin\Business\UserLoginBusinessFacadeInterface;
use App\Components\UserLogin\Persistence\DTO\UserLoginDto;
use App\Core\RedirectInterface;
use App\Core\SessionHandlerInterface;
use App\Core\ViewInterface;

readonly class LoginController implements UserLoginControllerInterface
{


    public function __construct(
        private UserLoginBusinessFacadeInterface $userLoginBusinessFacade,
        private RedirectInterface $redirect,
    ) {
    }

    public function load(ViewInterface $view): void
    {
        $userLoginDto = null;
        $errorsDTO = null;

        if (($_SERVER['REQUEST_METHOD'] === 'POST') && $_POST['loginButton'] === 'login') {
            $userLoginDto = new UserLoginDto();
            $userLoginDto->email = htmlspecialchars($_POST['email'] ?? '');
            $userLoginDto->password = htmlspecialchars($_POST['password'] ?? '');
            $errorsDTO = $this->userLoginBusinessFacade->loginUser($userLoginDto);

            if (!$errorsDTO instanceof ErrorsDTO) {
                $this->redirect->to('');
            }
        }
        $view->addParameter('errors', $errorsDTO);
        $view->addParameter('userLoginDto', $userLoginDto);
        $view->setTemplate('login.twig');
    }
}