<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordReset;

use App\Components\PasswordReset\Persistence\DTOs\MailDTO;
use App\Components\PasswordReset\Persistence\DTOs\ResetErrorDTO;

class ResetCoordinator
{


    public function __construct()
    {
    }


    public function coordinateResetPassword(MailDTO $mailDTO): ResetErrorDTO|true
    {

        //check existing entry in db
        //check time stamp
        //check input for validation
        //create ResetDTO if needed



        //reset password
        return true;
    }
}