<?php
declare(strict_types=1);

namespace App\Business\Inventory\Domain\Event;

use App\Business\EventInterface;
use App\Business\Value\Price;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ItemAdded implements EventInterface
{
    public static function fromJson(array $data): EventInterface
    {
        try {
            return new self(
                Uuid::fromString($data['uuid']),
                $data['name'],
                new Price($data['price']),
            );
        } catch (\InvalidArgumentException $err) {
            throw new \InvalidArgumentException("Incompatible json given for " . __CLASS__, $err->getCode(), $err);
        }
    }

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

    public function jsonSerialize(): mixed
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'price' => $this->price->getPriceInCents(),
        ];
    }
}
