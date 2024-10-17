<?php

declare(strict_types=1);

namespace App\Components\UserRegister\Communication\Controller;


use App\Components\User\Persistence\DTOs\ErrorsDTO;
use App\Components\User\Persistence\DTOs\UserDTO;
use App\Components\User\Persistence\Mapper\UserMapperInterface;
use App\Components\UserRegister\Business\UserRegisterBusinessFacadeInterface;
use App\Components\Validation\ValidationInterface;
use App\Core\RedirectInterface;
use App\Core\ViewInterface;

readonly class RegisterController implements UserRegisterControllerInterface
{
    public function __construct(
        private UserRegisterBusinessFacadeInterface $userRegisterBusinessFacade,
        private ValidationInterface $validation,
        private UserMapperInterface $userMapper,
        private RedirectInterface $redirect,

    ) {
    }


    public function load(ViewInterface $view): void
    {
        $userDTO = new UserDTO(-1,'', '', '', '');
        $errorsDTO = new ErrorsDTO('', '', '', '');

        if (($_SERVER['REQUEST_METHOD'] === 'POST') && $_POST['registerMe'] === 'push') {
            $temp['firstName'] = htmlspecialchars($_POST['fName'] ?? '');
            $temp['lastName'] = htmlspecialchars($_POST['lName'] ?? '');
            $temp['email'] = htmlspecialchars($_POST['email'] ?? '');
            $temp['password'] = htmlspecialchars($_POST['password'] ?? '');

            $userDTO = $this->userMapper->createDTO($temp);
            $errorsDTO = $this->validation->userRegisterGetErrors($userDTO);

            if ($this->validation->validateNoErrors($errorsDTO)) {
                $temp['password'] = password_hash($temp['password'], PASSWORD_DEFAULT);
                $userDTO= $this->userMapper->createDTO($temp);
                $this->userRegisterBusinessFacade->registerUser($userDTO);
                $this->redirect->to('');
            }
        }
        $view->setTemplate('register.twig');
        $view->addParameter('userDto', $userDTO);
        $view->addParameter('errors', $errorsDTO);
    }
}