<?php

declare(strict_types=1);

namespace App\Components\Shop\Persistence\Mapper;

use App\Components\Shop\Persistence\DTOs\ProductDto;

class ProductMapper
{
    public function createProductDto(
        string $category,
        string $name,
        string $imageLink,
        ?string $size = null,
        ?int $amount
    ): ProductDto {
        return new ProductDto(
            $name,
            $imageLink,
            $category,
            $size,
            null,
            $this->createProductLink($category, $name, $imageLink),
            $amount ?? 1
        );
    }

    private function createProductLink(string $category, string $name, string $imageLink): string
    {
        return '/index.php?page=details&category=' . $category . '&imageLink=' . $imageLink . '&name=' . $name;
    }
}