<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\ViewInterface;
use App\Model\UserRepository;

class LoginController implements Controller
{
    private UserRepository $repository;


    private array $templateVars;


    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
        $this->templateVars = [];
    }

    public function load(ViewInterface $view): array
    {
        $this->handlePost();
        //return $this->templateVars;
        return $this->templateVars;
    }

    /*
    private function loadLogin(): void
    {
        echo $this->twig->render('login.twig', $this->templateVars);
    }
*/
    private function handlePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] === 'POST') && $_POST['loginButton'] === 'login') {
            $filePath = __DIR__ . '/../../users.json';
            $existingUsers = $this->repository->getUsers();
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $errors = [];

            $errors = $this->checkAndGetErrors($existingUsers, $email, $password, $errors);


            if (empty($errors)) {
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

    private function checkAndGetErrors(array $existingUsers, string $email, string $password, array $errors): array
    {
        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['emailError'] = "Invalid email address.";
            } else {
                if (!$this->checkLoginData($existingUsers, $email, $password)) {
                    $errors['dataError'] = 'email and data is wrong';
                }
            }
        } else {
            $errors['emailEmptyError'] = "Email is required.";
        }
        if (empty($password)) {
            $errors['passwordEmptyError'] = "Password is empty.";
        }

        return $errors;
    }

    private function checkLoginData($existingUsers, $emailToCheck, $passwordToCheck): bool
    {
        foreach ($existingUsers as $user) {
            if (isset($user['password'], $user['email'])) {
                if ($user['email'] === $emailToCheck) {
                    return password_verify($passwordToCheck, $user['password']);
                }
            }
        }
        return false;
    }
}