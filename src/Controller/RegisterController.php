<?php

declare(strict_types=1);

namespace App\Controller;


use App\Core\RedirectInterface;
use App\Core\ValidationInterface;
use App\Core\View;
use App\Core\ViewInterface;
use App\Model\DTOs\ErrorsDTO;
use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapper;
use App\Model\Mapper\UserMapperInterface;
use App\Model\UserEntityManager;

class RegisterController implements Controller
{


    private UserDTO $userDTO;

    private ErrorsDTO $errorsDTO;

    private array $temp;

    private ViewInterface $view;

    public function __construct(
        private readonly UserEntityManager $userEntityManager,
        private readonly ValidationInterface $validation,
        private readonly UserMapperInterface $userMapper,
        private readonly RedirectInterface $redirect,

    ) {
    }


    public function load(ViewInterface $view): void
    {
        $this->userDTO = new UserDTO('','', '', '', '');
        $this->errorsDTO = new ErrorsDTO('', '', '', '');
        $this->view = $view;
        $this->handlePost();

    }

    private function handlePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] === 'POST') && $_POST['registerMe'] === 'push') {
            $this->temp['firstName'] = htmlspecialchars($_POST['fName'] ?? '');
            $this->temp['lastName'] = htmlspecialchars($_POST['lName'] ?? '');
            $this->temp['email'] = htmlspecialchars($_POST['email'] ?? '');
            $this->temp['password'] = htmlspecialchars($_POST['password'] ?? '');

            $this->userDTO = $this->userMapper->createDTO($this->temp);
            $this->errorsDTO = $this->validation->userRegisterGetErrors($this->userDTO);
            $inputValidation = $this->validation->validateErrors($this->errorsDTO);

            if ($inputValidation) {
                $this->userDTO = new UserDTO(
                    null,
                    $this->temp['firstName'],
                    $this->temp['lastName'],
                    $this->temp['email'],
                    password_hash($this->temp['password'], PASSWORD_DEFAULT),
                );
                $this->userEntityManager->saveUser($this->userDTO);
                $this->redirect->to('');
            }
        }
        $this->view->setTemplate('register.twig');
        $this->view->addParameter('userDto', $this->userDTO);
        $this->view->addParameter('errors', $this->errorsDTO);
    }



}