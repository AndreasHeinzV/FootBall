<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Validation;
use App\Core\View;
use App\Core\ViewInterface;
use App\Model\DTOs\ErrorsDTO;
use App\Model\DTOs\UserDTO;
use App\Model\Mapper\UserMapper;
use App\Model\UserRepository;
use PHPUnit\TextUI\XmlConfiguration\Validator;

class LoginController implements Controller
{
    private UserRepository $repository;
    public array $templateVars = [];
    public UserDTO $userDTO;
    public ErrorsDTO $errorsDTO;

    public Validation $validator;
    public UserMapper $userMapper;
    public array $temp;
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function load(ViewInterface $view): void
    {
        $this->handlePost();
        $this->setupView($view);
    }

    private function handlePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] === 'POST') && $_POST['loginButton'] === 'login') {
            $filePath = __DIR__ . '/../../users.json';
            $existingUsers = $this->repository->getUsers();
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $errors = [];

            $this->userDTO = $this->userMapper->createDTO($this->temp);
            $errorsDTO = $this->validator->getErrors($existingUsers, $this->userDTO);
            $validation = $this->validator->validateErrors($this->errorsDTO);

            if (empty($validation)) {
                $userName = $this->repository->getUserName($existingUsers, $email);

                $this->templateVars['email'] = '';
                $this->templateVars['password'] = '';
                $_SESSION["loginStatus"] = true;
                $_SESSION['userName'] = $userName;
                header("Location: /index.php");
            } else {
                $this->templateVars['errors'] = $errors;
                $this->templateVars['password'] = $password;
                $this->templateVars['email'] = $email;
                $_SESSION['loginStatus'] = false;
            }
        }
    }



    private function checkLoginData($existingUsers, $emailToCheck, $passwordToCheck): bool
    {
        foreach ($existingUsers as $user) {
            if (isset($user['password'], $user['email']) && $user['email'] === $emailToCheck) {
                return password_verify($passwordToCheck, $user['password']);
            }
        }
        return false;
    }

    private function setupView(ViewInterface $view): void
    {
        $view->addParameter('errors', $this->errorsDTO);
        $view->addParameter('userDto', $this->userDTO);

        $view->setTemplate('login.twig');
    }
}