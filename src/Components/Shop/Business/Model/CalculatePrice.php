<?php

declare(strict_types=1);

namespace App\Components\Shop\Business\Model;

use App\Components\Shop\Persistence\DTOs\ProductDto;

class CalculatePrice
{

    public function calculateProductPrice(ProductDto $productDto): ProductDto
    {
        if ($productDto->amount === null) {
            return $productDto;
        }

        if ($productDto->category === 'cup') {
            $productDto->price = 9.99 * $productDto->amount;
        }
        if ($productDto->category === 'scarf') {
            $productDto->price = 19.99 * $productDto->amount;
        }
        if ($productDto->category === 'soccerJersey') {
            if ($productDto->size === null) {
                return $productDto;
            }
            $size = strtoupper($productDto->size);
            $productDto->price = match ($size) {
                'XS' => 9.99 * $productDto->amount,
                'S' => 14.99 * $productDto->amount,
                'M' => 19.99 * $productDto->amount,
                'L' => 24.99 * $productDto->amount,
                'XL' => 29.99 * $productDto->amount,
                'XXL' => 34.99 * $productDto->amount,
                default => -1 * $productDto->amount,
            };
        }

        return $productDto;
    }

}