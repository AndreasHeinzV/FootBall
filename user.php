<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, []);

$templateVars = [];
$filePath = __DIR__ . '/users.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['fName'] ?? '';
    $lastName = $_POST['lName'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $errors = [];


    if (empty($firstName)) {
        $errors['firstNameEmptyError'] = "First name is empty.";
    } else {
        $templateVars['firstName'] = htmlspecialchars($firstName);
    }


    if (empty($lastName)) {
        $errors['lastNameEmptyError'] = "Last name is empty.";
    } else {
        $templateVars['lastName'] = htmlspecialchars($lastName);
    }


    if (!empty($email)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['emailError'] = "Invalid email address.";
        } else {
            $templateVars['email'] = htmlspecialchars($email);

            $existingUsers = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];

            if (checkDuplicateMail($existingUsers, $email)) {
                $errors['emailError'] = 'This email is already registered.';
            }
        }
    }else{
        $errors['emailEmptyError'] = "Email is required.";
    }


    if (empty($password)) {
        $errors['passwordEmptyError'] = "Password is empty.";
    } else {
        if (checkLength($password)) {
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


    if (empty($errors)) {
        $userData = [
            'firstName' => htmlspecialchars($firstName),
            'lastName' => htmlspecialchars($lastName),
            'email' => htmlspecialchars($email),
            'password' => password_hash($password, PASSWORD_DEFAULT), // Hash the password securely
        ];


        $filePath = __DIR__ . '/users.json';


        if (file_exists($filePath)) {
            $existingUsers = json_decode(file_get_contents($filePath), true);
        } else {
            $existingUsers = [];
        }


        $existingUsers[] = $userData;
        $jsonUserData = json_encode($existingUsers, JSON_PRETTY_PRINT);
        file_put_contents($filePath, $jsonUserData);

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

echo $twig->render('user.twig', $templateVars);

function checkLength($value): bool
{
    return strlen($value) < 7;
}


function checkDuplicateMail($existingUsers, $mailToCheck): bool
{
    foreach ($existingUsers as $user) {
        if (isset($user['email']) && $user['email'] === $mailToCheck) {
            return true;
        }
    }
    return false;
}


