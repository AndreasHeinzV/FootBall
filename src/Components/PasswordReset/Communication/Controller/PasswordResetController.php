<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Communication\Controller;

use App\Components\PasswordReset\Business\PasswordResetBusinessFacadeInterface;
use App\Core\ViewInterface;

class PasswordResetController
{
    public function __construct(private PasswordResetBusinessFacadeInterface $passwordResetBusinessFacade)
    {
    }

    public function load(ViewInterface $view)
    {

        $view->setTemplate('password-reset/password-reset.html');
        $view->addParameter('a' ,' ');
    }

}