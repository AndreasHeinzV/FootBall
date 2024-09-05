<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\RedirectInterface;
use App\Core\SessionHandler;
use App\Core\Validation;
use App\Core\View;
use App\Core\ViewInterface;
use App\Model\DTOs\ErrorsDTO;
use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapper;
use App\Model\Mapper\UserMapperInterface;
use App\Model\UserRepository;
use PHPUnit\TextUI\XmlConfiguration\Validator;

class LoginController implements Controller
{


    public UserDTO $userDTO;
    public ErrorsDTO $errorsDTO;

    public array $temp;

    public function __construct(
        public UserRepository $repository,
        public UserMapperInterface $userMapper,
        public Validation $validator,
        public SessionHandler $sessionHandler,
        public RedirectInterface $redirect,
    ) {
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
        if (($_SERVER['REQUEST_METHOD'] === 'POST') && $_POST['loginButton'] === 'login') {
            $filePath = __DIR__ . '/../../users.json';

            $existingUsers = $this->repository->getUsers();
            $this->temp['email'] = htmlspecialchars($_POST['email'] ?? '');
            $this->temp['password'] = htmlspecialchars($_POST['password'] ?? '');

            $this->userDTO = $this->userMapper->createDTO($this->temp);
            $this->errorsDTO = $this->validator->userLoginGetErrors($existingUsers, $this->userDTO);
            $validation = $this->validator->validateErrors($this->errorsDTO);

            if ($validation) {
                $this->sessionHandler->startSession();
                $this->userDTO = $this->repository->getUser($existingUsers, $this->userDTO->email);
                $this->sessionHandler->setUserDTO($this->userDTO);
                $this->sessionHandler->setUserDTO($this->userDTO);
               $this->redirect->to('index.php');
            }
        }
    }

    private function setupView(ViewInterface $view): void
    {
        $view->addParameter('errors', $this->errorsDTO);
        $view->addParameter('userDto', $this->userDTO);
        $view->setTemplate('login.twig');
    }


}