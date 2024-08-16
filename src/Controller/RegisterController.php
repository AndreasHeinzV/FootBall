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

    private UserEntityManager $entityManager;
    private Environment $twig;
    private string $filePath;
    private array $templateVars;
    private Validation $validation;

    private string $firstName;

    private string $lastName;
    private string $email;
    private string $password;
    private array $errors;


    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../View');
        $this->twig = new Environment($loader, []);

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
        $this->handlePost();
    }


    private function handlePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] === 'POST') && $_POST['registerMe'] === 'push') {
            $this->firstName = $_POST['fName'] ?? '';
            $this->lastName = $_POST['lName'] ?? '';
            $this->email = $_POST['email'] ?? '';
            $this->password = $_POST['password'] ?? '';
            $this->errors = [];
            $this->checkForErrors();
            if (empty($this->errors)) {
                $userData = [
                    'firstName' => htmlspecialchars($this->firstName),
                    'lastName' => htmlspecialchars($this->lastName),
                    'email' => htmlspecialchars($this->email),
                    'password' => password_hash($this->password, PASSWORD_DEFAULT), // Hash the password securely
                ];
                $this->entityManager->save($userData, $this->email, $this->filePath);
                $this->setTempVarsDefault();
                header('Location: /');
            } else {
                $this->setTempVars();
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

    private function setTempVars(): void
    {
        $this->templateVars['errors'] = $this->errors;
        $this->templateVars['password'] = $this->password;
        $this->templateVars['firstName'] = $this->firstName;
        $this->templateVars['lastName'] = $this->lastName;
        $this->templateVars['email'] = $this->email;
    }


    private function checkForErrors(): void
    {
        if (empty($this->firstName)) {
            $this->errors['firstNameEmptyError'] = "First name is empty.";
        }

        if (empty($this->lastName)) {
            $this->errors['lastNameEmptyError'] = "Last name is empty.";
        }

        if (!empty($this->email)) {
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                $this->errors['emailError'] = "Invalid email address.";
            }
        } else {
            $this->errors['emailEmptyError'] = "Email is required.";
        }


        if (empty($this->password)) {
            $this->errors['passwordEmptyError'] = "Password is empty.";
        } else {
            if ($this->validation->checkPasswordLength($this->password)) {
                $this->errors['passwordLengthError'] = "Password needs to be at least 7 characters long.";
            }
            if (!preg_match('/[0-9]/', $this->password)) {
                $this->errors['passwordNumberError'] = "Password must include at least one number.";
            }
            if (!preg_match('/[!?*#@$%^&]/', $this->password)) {
                $this->errors['passwordSpecialError'] = "Password must include at least one special character like ?!*$#@%^&.";
            }
            if (!preg_match('/[A-Z]/', $this->password)) {
                $this->errors['passwordUpperError'] = "Password must include at least one uppercase letter.";
            }
            if (!preg_match('/[a-z]/', $this->password)) {
                $this->errors['passwordLowerError'] = "Password must include at least one lowercase letter.";
            }
        }
    }
}