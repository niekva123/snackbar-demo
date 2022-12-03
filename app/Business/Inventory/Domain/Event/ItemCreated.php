<?php
declare(strict_types=1);

namespace App\Business\Inventory\Domain\Event;

use App\Business\EventInterface;
use App\Business\Value\Price;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ItemCreated implements EventInterface
{
    public function __construct(
        private readonly UuidInterface $uuid,
        private readonly string $name,
        private readonly Price $price,
    ) {}

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
}
