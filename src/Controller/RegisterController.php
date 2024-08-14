<?php

namespace App\Controller;


use App\Model\UserEntityManager;

use App\Model\UserRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class RegisterController
{

    private UserRepository $repository;
    private UserEntityManager $entityManager;
    private Environment $twig;
    private string $filePath;

    public function __construct($templatePath)
    {
        $loader = new FilesystemLoader($templatePath);
        $this->twig = new Environment($loader, []);
        $this->repository = new UserRepository();
        $this->entityManager = new UserEntityManager();
        $this->filePath = __DIR__ . '/../../users.json';
        $templateVars = [];
        $this->loadRegister($this->doRegister($templateVars));
    }

    function loadRegister($templateVars): void
    {
        echo $this->twig->render('register.twig', $templateVars);
    }

    function doRegister($templateVars): array
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


                $existingUsers = $this->repository->getUsers($this->filePath);
                $existingUsers[] = $userData;
                $this->entityManager->safeUser($this->filePath, $existingUsers);


                $templateVars['firstName'] = '';
                $templateVars['lastName'] = '';
                $templateVars['email'] = '';
                $templateVars['password'] = '';
                $templateVars['successMessage'] = 'User data saved successfully.';
            } else {
                $templateVars['errors'] = $errors;
                $templateVars['password'] = $password;
                $templateVars['firstName'] = $firstName;
                $templateVars['lastName'] = $lastName;
                $templateVars['email'] = $email;
            }
        }
        return $templateVars;
    }

    private
    function checkLength(
        $value
    ): bool {
        return strlen($value) < 7;
    }


    private
    function checkDuplicateMail(
        $existingUsers,
        $mailToCheck
    ): bool {
        foreach ($existingUsers as $user) {
            if (isset($user['email']) && $user['email'] === $mailToCheck) {
                return true;
            }
        }
        return false;
    }

    private function checkForErrors($errors, $firstName, $lastName, $email, $password): array
    {
        if (empty($firstName)) {
            $errors['firstNameEmptyError'] = "First name is empty.";
        }

        if (empty($lastName)) {
            $errors['lastNameEmptyError'] = "Last name is empty.";
        }

        if (!empty($email)) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['emailError'] = "Invalid email address.";
            } else {
                $existingUsers = file_exists($this->filePath) ? json_decode(
                    file_get_contents($this->filePath),
                    true
                ) : [];

                if ($this->checkDuplicateMail($existingUsers, $email)) {
                    $errors['emailError'] = 'This email is already registered.';
                }
            }
        } else {
            $errors['emailEmptyError'] = "Email is required.";
        }


        if (empty($password)) {
            $errors['passwordEmptyError'] = "Password is empty.";
        } else {
            if ($this->checkLength($password)) {
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