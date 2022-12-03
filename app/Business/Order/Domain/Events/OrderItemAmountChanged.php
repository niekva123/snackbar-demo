<?php
declare(strict_types=1);

namespace App\Business\Order\Domain\Events;

use App\Business\EventInterface;
use Ramsey\Uuid\UuidInterface;

class OrderItemAmountChanged implements EventInterface
{
    public function __construct(
        private readonly UuidInterface $orderItemUuid,
        private readonly int $amount,
    ) {}

    public function getOrderItemUuid(): UuidInterface
    {
        return $this->orderItemUuid;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}
