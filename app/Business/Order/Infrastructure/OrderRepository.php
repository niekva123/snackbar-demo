<?php
declare(strict_types=1);

namespace App\Business\Order\Infrastructure;

use App\Business\Order\Domain\Entity\Order;
use App\Business\Order\Domain\Events\OrderCreated;
use App\Business\Order\Domain\Events\OrderItemAdded;
use App\Business\Order\Domain\Events\OrderItemAmountChanged;
use App\Business\Order\Domain\Events\OrderItemRemoved;
use App\Business\Order\Domain\Repository\OrderRepositoryInterface;
use App\Business\Order\Domain\Service\InventoryInterface;
use App\Business\Value\Price;
use App\Models\OrderItem;
use Illuminate\Events\Dispatcher;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private readonly InventoryInterface $itemPriceSupplier,
        private readonly Dispatcher         $eventDispatcher,
    ) {}

    public function getOrder(UuidInterface $orderUuid): Order
    {
        $order = \App\Models\Order::with('orderItems')->whereOrderUuid($orderUuid->toString())->get();

        $orderItems = $order->orderItems->map(fn (OrderItem $orderItem) => new \App\Business\Order\Domain\Value\OrderItem(
                Uuid::fromString($orderItem->uuid),
                Uuid::fromString($orderItem->item_uuid),
                new Price($orderItem->price),
                $orderItem->amount,
            ))
        ;
        return new Order($orderUuid, Uuid::fromString($order->snackbar_uuid), $this->itemPriceSupplier, $orderItems);
    }

    public function save(Order $order): void
    {
        $events = $order->popNewEvents();
        foreach ($events as $event) {
            match (get_class($event)) {
                OrderCreated::class => $this->handleOrderCreated($event),
                OrderItemAdded::class => $this->handleOrderItemAdded($event),
                OrderItemAmountChanged::class => $this->handleOrderItemAmountChanged($event),
                OrderItemRemoved::class => $this->handleOrderItemRemoved($event),
            };

            $this->eventDispatcher->dispatch($event);
        }
    }

    private function handleOrderCreated(OrderCreated $event): void
    {
        $orderModel = new \App\Models\Order();
        $orderModel->uuid = $event->getOrderUuid()->toString();
        $orderModel->snackbar_uuid = $event->getSnackbarUuid()->toString();
        $orderModel->saveOrFail();
    }

    private function handleOrderItemAdded(OrderItemAdded $event): void
    {
        $orderItem = new OrderItem();
        $orderItem->uuid = $event->getOrderItemUuid()->toString();
        $orderItem->order_uuid = $event->getOrderUuid()->toString();
        $orderItem->item_uuid = $event->getItemUuid()->toString();
        $orderItem->amount = $event->getAmount();
        $orderItem->price = $event->getPrice()->getPriceInCents();
        $orderItem->saveOrFail();
    }

    private function handleOrderItemAmountChanged(OrderItemAmountChanged $event): void
    {
        $orderItem = OrderItem::findOrFail($event->getOrderItemUuid()->toString());
        $orderItem->amount = $event->getAmount();
        $orderItem->saveOrFail();
    }

    private function handleOrderItemRemoved(OrderItemRemoved $event): void
    {
        OrderItem::whereUuid($event->getOrderItemUuid()->toString())->delete();
    }
}
