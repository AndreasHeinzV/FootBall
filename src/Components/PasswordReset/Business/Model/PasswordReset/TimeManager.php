<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordReset;

use App\Components\PasswordReset\Persistence\DTOs\MailDTO;

class TimeManager
{

    public function setTimestamp(MailDTO $mailDTO): MailDTO
    {
        $mailDTO->timestamp = time();
        return $mailDTO;
    }

    public function compareTimestamp(?int $timestamp): bool
    {
        $currentTimestamp = time();
        $timestampLimit = $timestamp + (2 * 60 * 60);
        return  $timestampLimit > $currentTimestamp;
    }

}