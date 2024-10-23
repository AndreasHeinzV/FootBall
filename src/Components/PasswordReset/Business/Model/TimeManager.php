<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model;

use App\Components\PasswordReset\Persistence\DTOs\MailDTO;

class TimeManager
{

    public function setTimestamp(MailDTO $mailDTO): MailDTO
    {
        $mailDTO->timestamp = time();
       return $mailDTO;
    }

}