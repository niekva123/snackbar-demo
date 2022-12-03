<?php
declare(strict_types=1);

namespace App\Business\Order\Domain\Events;

use App\Business\EventInterface;
use App\Business\Value\Price;
use Ramsey\Uuid\UuidInterface;

class OrderItemAdded implements EventInterface
{
    public function __construct(
        private readonly UuidInterface $orderUuid,
        private readonly UuidInterface $itemUuid,
        private readonly UuidInterface $orderItemUuid,
        private readonly Price $price,
        private readonly int $amount,
    ) {}

    public function getOrderUuid(): UuidInterface
    {
        return $this->orderUuid;
    }

    public function getItemUuid(): UuidInterface
    {
        return $this->itemUuid;
    }

    public function getOrderItemUuid(): UuidInterface
    {
        return $this->orderItemUuid;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
