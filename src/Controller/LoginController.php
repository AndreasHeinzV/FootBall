<?php

namespace App\Controller;


use App\Model\UserRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class LoginController
{
    private UserRepository $repository;
    private Environment $twig;


    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../View');
        $this->twig = new Environment($loader, []);
        $this->repository = new UserRepository();
        $templateVars = [];
        $this->loadLogin($this->doLogin($templateVars));
    }

    public function doLogin($templateVars): array
    {
        $filePath = __DIR__ . '/../../users.json';
        $existingUsers = $this->repository->getUsers($filePath);


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $errors = [];

            $errors = $this->checkAndGetErrors($existingUsers, $email, $password, $errors);


            if (empty($errors)) {
                $templateVars['email'] = '';
                $templateVars['password'] = '';
                $_SESSION["loginStatus"] = true;
                $_SESSION['userName'] = $this->repository->getUserName($existingUsers, $email);
                header("Location: /index.php");
            } else {
                $templateVars['errors'] = $errors;
                $templateVars['password'] = $password;
                $templateVars['email'] = $email;
                $_SESSION['loginStatus'] = false;
            }
        }
        return $templateVars;
    }

    function loadLogin($templateVars): void
    {
        echo $this->twig->render('login.twig', $templateVars);
    }

    private function checkAndGetErrors($existingUsers, $email, $password, $errors): array
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


    private function checkErrors($existingUsers, $email, $password): bool
    {
        return true;
    }

    private function getErrors($existingUsers, $email, $password): array
    {
        $errors = [];

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
            if (isset($user['password']) && isset($user['email'])) {
                if ($user['email'] === $emailToCheck) {
                    return password_verify($passwordToCheck, $user['password']);
                }
            }
        }
        return false;
    }
}