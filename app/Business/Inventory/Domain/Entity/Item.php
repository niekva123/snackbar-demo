<?php
declare(strict_types=1);

namespace App\Business\Inventory\Domain\Entity;

use App\Business\Inventory\Domain\Exception\ItemNameException;
use App\Business\Value\Price;
use Ramsey\Uuid\UuidInterface;

class Item
{
    public function __construct(
        private readonly UuidInterface $uuid,
        private string $name,
        private Price $price,
    ) {
        if (trim($this->name) === "") {
            throw ItemNameException::forEmptyName();
        }
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrice(Price $price): void
    {
        $this->price = $price;
    }
}
