<?php

declare(strict_types=1);

namespace App\Components\PasswordReset\Business\Model\PasswordFailed;

use App\Components\PasswordReset\Persistence\DTOs\MailDTO;

class EmailBuilder
{
    public function buildMail(MailDTO $mailDTO): MailDTO{
        $link = "http://localhost:8000/resetPassword?page=resetPassword&ts=" . $mailDTO->timestamp . "&actionId=" . $mailDTO->actionId;
        $mailDTO->message = "Click here to reset your Password <a href='{$link}'>Access Your Content</a>. Please note that the link will expire in 2 hours.";
        return $mailDTO;
    }
}