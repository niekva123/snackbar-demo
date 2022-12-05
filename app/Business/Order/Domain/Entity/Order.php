<?php
declare(strict_types=1);

namespace App\Business\Order\Domain\Entity;

use App\Business\AggregateRoot;
use App\Business\Order\Domain\Events\OrderCreated;
use App\Business\Order\Domain\Events\OrderItemAdded;
use App\Business\Order\Domain\Events\OrderItemAmountChanged;
use App\Business\Order\Domain\Events\OrderItemRemoved;
use App\Business\Order\Domain\Exception\InventoryException;
use App\Business\Order\Domain\Exception\OrderItemException;
use App\Business\Order\Domain\Service\InventoryInterface;
use App\Business\Order\Domain\Value\OrderItem;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Order extends AggregateRoot
{
    public static function newOrder(UuidInterface $snackbarUuid, InventoryInterface $inventory): self
    {
        $uuid = Uuid::uuid4();
        $order = new Order($uuid, $snackbarUuid, $inventory, []);
        $order->newEvent(new OrderCreated($uuid, $snackbarUuid));
        return $order;
    }

    /**
     * @var OrderItem[]
     */
    private array $orderItems;

    /**
     * @param OrderItem[] $orderItems
     */
    public function __construct(
        private readonly UuidInterface      $orderUuid,
        private readonly UuidInterface      $snackbarUuid,
        private readonly InventoryInterface $inventory,
        array                               $orderItems,
    ) {
        $this->orderItems = [];
        //Make order items accessible by uuid
        foreach ($orderItems as $orderItem) {
            $this->orderItems[$orderItem->getOrderItemUuid()->toString()] = $orderItem;
        }
    }

    /**
     * @throws InventoryException
     */
    public function addOrderItem(UuidInterface $itemUuid, int $amount): UuidInterface
    {
        $price = $this->inventory->getCurrentItemPrice($this->snackbarUuid, $itemUuid);
        $orderItemUuid = Uuid::uuid4();
        $this->orderItems[] = new OrderItem($orderItemUuid, $itemUuid, $price, $amount);

        $this->newEvent(new OrderItemAdded($this->orderUuid, $itemUuid, $orderItemUuid, $price, $amount));

        return $orderItemUuid;
    }

    public function changeOrderItemAmount(UuidInterface $orderItemUuid, int $amount): void
    {
        $orderItem = $this->getOrderItem($orderItemUuid);
        if ($orderItem->getAmount() === $amount) {
            return;//Amount is already the given amount. No update
        }
        $orderItem->changeAmount($amount);
        $this->newEvent(new OrderItemAmountChanged($orderItemUuid, $amount));
    }

    public function removeOrderItem(UuidInterface $uuid): void
    {
        //Make sure order item is present
        $this->getOrderItem($uuid);
        unset($this->orderItems[$uuid->toString()]);

        $this->newEvent(new OrderItemRemoved($uuid));
    }

    public function getOrderUuid(): UuidInterface
    {
        return $this->orderUuid;
    }

    public function getSnackbarUuid(): UuidInterface
    {
        return $this->snackbarUuid;
    }

    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    public function getOrderItem(UuidInterface $orderItemUuid): OrderItem
    {
        return $this->orderItems[$orderItemUuid->toString()] ?? throw OrderItemException::notFound($orderItemUuid);
    }
}
