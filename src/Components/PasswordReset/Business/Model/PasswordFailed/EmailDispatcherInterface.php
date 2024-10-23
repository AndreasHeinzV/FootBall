<?php

namespace App\Components\PasswordReset\Business\Model\PasswordFailed;

use App\Components\PasswordReset\Persistence\DTOs\MailDTO;

interface EmailDispatcherInterface
{
    public function sendMail(MailDTO $mailDTO): bool;
}