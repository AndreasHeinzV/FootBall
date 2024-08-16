<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Validation;
use App\Model\UserEntityManager;
use App\Model\UserRepository;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class RegisterController implements Controller
{

    private UserRepository $repository;
    private UserEntityManager $entityManager;
    private Environment $twig;
    private string $filePath;
    private array $templateVars;
    private Validation $validation;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../View');
        $this->twig = new Environment($loader, []);
        $this->repository = new UserRepository();
        $this->entityManager = new UserEntityManager();
        $this->filePath = __DIR__ . '/../../users.json';
        $this->templateVars = [];
        $this->validation = new Validation();
    }

    private function loadRegister(): void
    {
        try {
            echo $this->twig->render('register.twig', $this->templateVars);
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            echo $e->getMessage();
        }
    }

    public function load(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $_POST['fName'] ?? '';
            $lastName = $_POST['lName'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $errors = [];

            $errors = $this->checkForErrors($errors, $firstName, $lastName, $email, $password);
            if (empty($errors)) {
                $userData = [
                    'firstName' => htmlspecialchars($firstName),
                    'lastName' => htmlspecialchars($lastName),
                    'email' => htmlspecialchars($email),
                    'password' => password_hash($password, PASSWORD_DEFAULT), // Hash the password securely
                ];
                $this->entityManager->save($userData, $email, $this->filePath);
                $this->setTempVarsDefault();
                header('Location: /');
            } else {
                $this->setTempVars($errors, $firstName, $lastName, $email, $password);
            }
        }
        $this->loadRegister();
    }

    private function setTempVarsDefault(): void
    {
        $this->templateVars['firstName'] = '';
        $this->templateVars['lastName'] = '';
        $this->templateVars['email'] = '';
        $this->templateVars['password'] = '';
        $this->templateVars['successMessage'] = 'User data saved successfully.';
    }

    private function setTempVars(
        array $errors,
        string $firstName,
        string $lastName,
        string $email,
        string $password
    ): void {
        $this->templateVars['errors'] = $errors;
        $this->templateVars['password'] = $password;
        $this->templateVars['firstName'] = $firstName;
        $this->templateVars['lastName'] = $lastName;
        $this->templateVars['email'] = $email;
    }


    private function checkForErrors(
        array $errors,
        string $firstName,
        string $lastName,
        string $email,
        string $password
    ): array {
        if (empty($firstName)) {
            $errors['firstNameEmptyError'] = "First name is empty.";
        }

        if (empty($lastName)) {
            $errors['lastNameEmptyError'] = "Last name is empty.";
        }

        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['emailError'] = "Invalid email address.";
            }
        } else {
            $errors['emailEmptyError'] = "Email is required.";
        }


        if (empty($password)) {
            $errors['passwordEmptyError'] = "Password is empty.";
        } else {
            if ($this->validation->checkPasswordLength($password)) {
                $errors['passwordLengthError'] = "Password needs to be at least 7 characters long.";
            }
            if (!preg_match('/[0-9]/', $password)) {
                $errors['passwordNumberError'] = "Password must include at least one number.";
            }
            if (!preg_match('/[!?*#@$%^&]/', $password)) {
                $errors['passwordSpecialError'] = "Password must include at least one special character like ?!*$#@%^&.";
            }
            if (!preg_match('/[A-Z]/', $password)) {
                $errors['passwordUpperError'] = "Password must include at least one uppercase letter.";
            }
            if (!preg_match('/[a-z]/', $password)) {
                $errors['passwordLowerError'] = "Password must include at least one lowercase letter.";
            }
        }
        return $errors;
    }
}