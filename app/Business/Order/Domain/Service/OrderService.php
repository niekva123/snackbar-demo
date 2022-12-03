<?php
declare(strict_types=1);

namespace App\Business\Order\Domain\Service;

use App\Business\Order\Domain\Entity\Order;
use App\Business\Order\Domain\Repository\OrderRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

class OrderService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly InventoryInterface       $inventory,
    ) {}

    public function createOrder(UuidInterface $snackbarUuid): UuidInterface
    {
        if (!$this->inventory->inventoryExists($snackbarUuid)) {
            throw new \InvalidArgumentException("Invalid snackbar uuid $snackbarUuid given");
        }
        $order = Order::newOrder($snackbarUuid, $this->inventory);
        $this->orderRepository->save($order);

        return $order->getOrderUuid();
    }

    public function addOrderItem(UuidInterface $orderUuid, UuidInterface $itemUuid, int $amount): UuidInterface
    {
        $order = $this->orderRepository->getOrder($orderUuid);

        $orderItemUuid = $order->addOrderItem($itemUuid, $amount);
        $this->orderRepository->save($order);

        return $orderItemUuid;
    }

    public function changeOrderItemAmount(UuidInterface $orderUuid, UuidInterface $orderItemUuid, int $amount): void
    {
        $order = $this->orderRepository->getOrder($orderUuid);
        $order->changeOrderItemAmount($orderItemUuid, $amount);
        $this->orderRepository->save($order);
    }

    public function removeOrderItem(UuidInterface $orderUuid, UuidInterface $orderItemUuid): void
    {
        $order = $this->orderRepository->getOrder($orderUuid);
        $order->removeOrderItem($orderItemUuid);
        $this->orderRepository->save($order);
    }
}
