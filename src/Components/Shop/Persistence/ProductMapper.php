<?php

declare(strict_types=1);

namespace App\Components\Shop\Persistence;

use App\Components\Shop\Persistence\DTOs\ProductDto;

class ProductMapper
{
    public function createProductDto(string $category, string $name, string $imageLink): ProductDto
    {
        return new ProductDto(
            $name,
            $imageLink,
            $category,
            null,
            null,
            $this->createProductLink($category, $name, $imageLink)
        );
    }

    private function createProductLink(string $category, string $name, string $imageLink): string
    {
        return 'http://localhost:8000/index.php?page=details&category=' . $category . '&imageLink=' . $imageLink . '&name=' . $name;
    }
}