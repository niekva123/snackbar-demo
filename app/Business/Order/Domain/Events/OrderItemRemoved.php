<?php
declare(strict_types=1);

namespace App\Business\Order\Domain\Events;

use App\Business\EventInterface;
use Ramsey\Uuid\UuidInterface;

class OrderItemRemoved implements EventInterface
{
    public function __construct(
        private readonly UuidInterface $orderItemUuid,
    ) {}

    public function getOrderItemUuid(): UuidInterface
    {
        return $this->orderItemUuid;
    }
}
