<?php

use App\Controller\RegisterController;

session_start();
require_once __DIR__ . '/vendor/autoload.php';
$templatePath = __DIR__ . '/src/View';
 new RegisterController($templatePath);





