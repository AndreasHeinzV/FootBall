<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\Validation;
use App\Core\ValidationInterface;
use App\Model\UserEntityManager;

class RegisterController implements Controller
{


    private string $filePath;
    private array $templateVars;
    private ValidationInterface $validation;

    private string $firstName;

    private string $lastName;
    private string $email;
    private string $password;
    private array $errors;

    private UserEntityManager $userEntityManager;

    public function __construct(UserEntityManager $userEntityManager, ValidationInterface $validation)
    {
        $this->userEntityManager = $userEntityManager;
        $this->filePath = __DIR__ . '/../../users.json';
        $this->templateVars = [];
        $this->validation = $validation;
    }


    public function load($view): array
    {
        $this->handlePost();
        return $this->templateVars;
    }


    private function handlePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] === 'POST') && $_POST['registerMe'] === 'push') {
           // $this->errors = [];

            $userData = [
                'firstName' => htmlspecialchars($_POST['fName'] ?? ''),
                'lastName' => htmlspecialchars($_POST['lName'] ?? ''),
                'email' => htmlspecialchars($_POST['lName'] ?? ''),
                'password' => password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT), // Hash the password securely
            ];
            $this->errors = $this->validation->checkForErrors($userData);

            if (empty($this->errors)) {
                $this->userEntityManager->save($userData, $this->email, $this->filePath);
                $this->setTempVarsDefault();
                header('Location: /');
            } else {
                $this->setTempVars();
            }
        }
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
}