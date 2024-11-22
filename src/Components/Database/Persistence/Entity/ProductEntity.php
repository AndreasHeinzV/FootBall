<?php

declare(strict_types=1);

namespace App\Components\Database\Persistence\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'cart_items')]
class ProductEntity
{

    #[Id]
    #[GeneratedValue(strategy: 'AUTO')]
    #[Column(type: 'integer', unique: true)]
    private ?int $productId;

    #[Column(type: 'string', length: 255)]
    private string $name;
    #[Column(type: 'integer')]
    private int $amount;

    #[Column(type: 'string', length: 255)]
    private ?string $size;

    #[Column(type: 'float')]
    private float $price;

    #[ManyToOne(targetEntity: UserEntity::class, inversedBy: 'cartItems')]
    #[Column(name: 'user_id', type: 'integer')]
    private int $userId;

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): ProductEntity
    {
        $this->userId = $userId;
        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): ProductEntity
    {
        $this->price = $price;
        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): ProductEntity
    {
        $this->size = $size;
        return $this;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): ProductEntity
    {
        $this->amount = $amount;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ProductEntity
    {
        $this->name = $name;
        return $this;
    }


}