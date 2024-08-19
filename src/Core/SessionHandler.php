<?php

declare(strict_types=1);

namespace App\Core;

class SessionHandler
{
    public function __construct()
    {
        $this->status = false;
    }

    private bool $status;

    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }


    public function getStatus()
    {
        return $this->status;
    }
}