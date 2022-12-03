<?php
declare(strict_types=1);

namespace App\Business\Order\Domain\Repository;

use App\Business\Order\Domain\Entity\Order;
use Ramsey\Uuid\UuidInterface;

interface OrderRepositoryInterface
{
    public function getOrder(UuidInterface $orderUuid): Order;

    public function save(Order $order): void;
}
