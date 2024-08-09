<?php
session_start();


require_once __DIR__ . '/vendor/autoload.php';

//ar_dump($_SESSION);


$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, []);

$templateVars = [];
$filePath = __DIR__ . '/users.json';

$existingUsers = file_exists($filePath) ? json_decode(file_get_contents($filePath), true) : [];

//if (isset($_POST['email'], $_POST['password'])) {
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $errors = [];


    if (!empty($email)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['emailError'] = "Invalid email address.";
        } else {
            $templateVars['email'] = htmlspecialchars($email);


            if (!checkLoginData($existingUsers, $email, $password)) {
                $errors['dataError'] = 'email and data is wrong';
            }
        }
    } else {
        $errors['emailEmptyError'] = "Email is required.";

    }
    if (empty($password)) {
        $errors['passwordEmptyError'] = "Password is empty.";
    } else {
        $templateVars['password'] = htmlspecialchars($password);


    }
    if (empty($errors)) {

        $templateVars['email'] = '';
        $templateVars['password'] = '';
        $templateVars['successMessage'] = 'Login works.';
      //  $_SESSION["user_email"] = $email;
        $_SESSION["loginStatus"] = true;
        $_SESSION['userName'] = getUserName($existingUsers,$email);

    } else {

        $templateVars['errors'] = $errors;
        $templateVars['password'] = $password;
        $templateVars['email'] = $email;
        $_SESSION['loginStatus'] = false;
    }
}

echo $twig->render('login.twig', $templateVars);

function getUsername($existingUsers, $email) {
        foreach ($existingUsers as $existingUser) {
            if ($existingUser['email'] === $email) {
               // echo  "Method: " . $existingUser['firstName']. "<br>";
                return $existingUser['firstName'];

            }
        }
    return '';
}

function checkLoginData($existingUsers, $emailToCheck, $passwordToCheck): bool
{
    foreach ($existingUsers as $user) {
        if (isset($user['password']) && isset($user['email']) ) {
            if($user['email'] === $emailToCheck ){
                if (password_verify($passwordToCheck, $user['password'])) {
                    return true;
                }
            }
        }

    }
    return false;
}
