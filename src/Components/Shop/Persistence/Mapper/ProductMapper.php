<?php

declare(strict_types=1);

namespace App\Components\Shop\Persistence\Mapper;

use App\Components\Shop\Persistence\DTOs\ProductDto;

class ProductMapper
{
    public function createProductDto(
        string $category,
        string $teamName,
        string $name,
        string $imageLink,
        ?string $size,
        ?int $amount,
        ?float $price
    ): ProductDto {
        return new ProductDto(
            $name,
            $teamName,
            $imageLink,
            $category,
            $size ?? null,
            $price ?? null,
            $this->createProductLink($category, $name, $imageLink, $teamName),
            $amount ?? 1
        );
    }

    private function createProductLink(string $category, string $name, string $imageLink, string $teamName): string
    {
        return '/index.php?page=details&category=' . $category . '&name=' . $name . '&teamName=' . $teamName . '&imageLink=' . $imageLink;
    }
}