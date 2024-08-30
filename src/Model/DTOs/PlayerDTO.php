<?php

declare(strict_types=1);

namespace App\Model\DTOs;

readonly class PlayerDTO
{
    public function __construct(
        public string $name,
        public string $position,
        public string $dateOfBirth,
        public string $nationality,
        public int $shirtNumber
    ) {
    }
}