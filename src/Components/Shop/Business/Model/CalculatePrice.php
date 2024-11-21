<?php

declare(strict_types=1);

namespace App\Components\Shop\Business\Model;

use App\Components\Shop\Persistence\DTOs\ProductDto;

class CalculatePrice
{

    public function calculateProductPrice(ProductDto $productDto): ProductDto
    {
        if ($productDto->category === 'cup') {
            $productDto->price = 9.99;
        }
        if ($productDto->category === 'scarf') {
            $productDto->price = 19.99;
        }
        if ($productDto->category === 'soccerJersey') {
            $size = strtoupper($productDto->size);
            $productDto->price = match ($size) {
                'XS' => 9.99,
                'S' => 14.99,
                'M' => 19.99,
                'L' => 24.99,
                'XL' => 29.99,
                'XXL' => 34.99,
                default => -1,
            };
        }
        return $productDto;
    }

}