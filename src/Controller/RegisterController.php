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


    private ValidationInterface $validation;


    private UserMapper $userMapper;
    private UserEntityManager $userEntityManager;
    private UserDTO $userDTO;

    private ErrorsDTO $errorsDTO;

    public RedirectInterface $redirect;
    public array $temp;


    public function __construct(
        UserEntityManager $userEntityManager,
        ValidationInterface $validation,
        UserMapperInterface $userMapper,
        RedirectInterface $redirect,

    ) {
        $this->userEntityManager = $userEntityManager;
        $this->userMapper = $userMapper;
        $this->validation = $validation;
        $this->redirect = $redirect;
    }


    public function load(ViewInterface $view): void
    {
        $this->userDTO = new UserDTO('', '', '', '');
        $this->errorsDTO = new ErrorsDTO('', '', '', '');
        $this->handlePost();
        $this->setupView($view);
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
                    $this->temp['firstName'],
                    $this->temp['lastName'],
                    $this->temp['email'],
                    password_hash($this->temp['password'], PASSWORD_DEFAULT),
                );
                $this->userEntityManager->save($this->userDTO);
               $this->redirect->to('');
            }
        }
    }


    private function setupView(Viewinterface $view): void
    {
        $view->setTemplate('register.twig');
        $view->addParameter('userDto', $this->userDTO);
        $view->addParameter('errors', $this->errorsDTO);
    }
}