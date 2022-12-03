<?php
declare(strict_types=1);

namespace App\Business\Order\Domain\Events;

use App\Business\EventInterface;
use Ramsey\Uuid\UuidInterface;

class OrderCreated implements EventInterface
{
    public function __construct(
        private readonly UuidInterface $orderUuid,
        private readonly UuidInterface $snackbarUuid,
    ) {}

    public function getOrderUuid(): UuidInterface
    {
        return $this->orderUuid;
    }

    public function getSnackbarUuid(): UuidInterface
    {
        return $this->snackbarUuid;
    }
}
