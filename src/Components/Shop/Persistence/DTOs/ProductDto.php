<?php

declare(strict_types=1);

namespace App\Components\Shop\Persistence\DTOs;

class ProductDto
{
    public function __construct(
        public string $name,
        public string $teamName,
        public string $imageLink,
        public string $category,
        public ?string $size,
        public ?float $price,
        public string $link,
        public ?int $amount,
    ) {
    }
}