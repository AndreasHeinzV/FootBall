<?php

use App\Controller\LoginController;

session_start();
require_once __DIR__ . '/vendor/autoload.php';
$templatePath = __DIR__ . '/src/View';
    new LoginController($templatePath);
