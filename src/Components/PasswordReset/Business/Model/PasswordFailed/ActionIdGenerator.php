<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordFailed;

use App\Components\PasswordReset\Persistence\DTOs\MailDTO;

class ActionIdGenerator
{

    public  function generate(MailDTO $mailDTO): MailDTO{
        $mailDTO->actionId = uniqid('', true);
        return $mailDTO;
    }
}