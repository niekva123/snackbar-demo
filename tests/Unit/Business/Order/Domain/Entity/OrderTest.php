<?php

namespace Tests\Unit\Business\Order\Domain\Entity;

use App\Business\Order\Domain\Entity\Order;
use App\Business\Order\Domain\Events\OrderCreated;
use App\Business\Order\Domain\Events\OrderItemAdded;
use App\Business\Order\Domain\Events\OrderItemAmountChanged;
use App\Business\Order\Domain\Events\OrderItemRemoved;
use App\Business\Order\Domain\Exception\InventoryException;
use App\Business\Order\Domain\Exception\OrderItemException;
use App\Business\Order\Domain\Service\InventoryInterface;
use App\Business\Order\Domain\Value\OrderItem;
use App\Business\Value\Price;
use Ramsey\Uuid\Uuid;
use Tests\UnitTestCase;

class OrderTest extends UnitTestCase
{
    private const ITEM_PRICE = 75;

    public function test_newOrder_should_trigger_creation_event(): void
    {
        $snackbarUuid = Uuid::uuid4();
        $order = Order::newOrder($snackbarUuid, $this->inventory());
        $this->assertEvent($order->popNewEvents(), 1, function ($event) use ($snackbarUuid) {
            $this->assertInstanceOf(OrderCreated::class, $event);
            $this->assertEquals($snackbarUuid->toString(), $event->getSnackbarUuid()->toString());
        });
    }

    public function test_addOrderItem(): void
    {
        $snackbarUuid = Uuid::uuid4();
        $orderUuid = Uuid::uuid4();
        $order = new Order(
            $orderUuid,
            $snackbarUuid,
            $this->inventory(new Price(self::ITEM_PRICE)),
            [],
        );
        $orderItemUuid = $order->addOrderItem(Uuid::uuid4(), 1);
        $this->assertCount(1, $order->getOrderItems());
        $this->assertEvent($order->popNewEvents(), 1, function ($event) use ($orderUuid, $snackbarUuid, $orderItemUuid) {
            $this->assertInstanceOf(OrderItemAdded::class, $event);
            $this->assertEquals($orderUuid->toString(), $event->getOrderUuid()->toString());
            $this->assertEquals($orderItemUuid->toString(), $event->getOrderItemUuid()->toString());
            $this->assertEquals(self::ITEM_PRICE, $event->getPrice()->getPriceInCents());
            $this->assertEquals(1, $event->getAmount());
        });
    }

    public function test_addOrderItem_throws_exception_when_item_is_not_found(): void
    {
        $snackbarUuid = Uuid::uuid4();

        $order = new Order(
            Uuid::uuid4(),
            $snackbarUuid,
            $this->emptyInventory(),
            [],
        );
        $this->expectException(InventoryException::class);
        $order->addOrderItem(Uuid::uuid4(), 1);
    }

    public function test_changeOrderItemAmount_should_trigger_an_event(): void
    {
        $orderItem = $this->orderItem(2);
        $order = new Order(
            Uuid::uuid4(),
            Uuid::uuid4(),
            $this->inventory(),
            [$orderItem],
        );
        $order->changeOrderItemAmount($orderItem->getOrderItemUuid(), 3);
        $this->assertEquals(3, $orderItem->getAmount());
        $this->assertEvent($order->popNewEvents(), 1, function ($event) use ($orderItem) {
            $this->assertInstanceOf(OrderItemAmountChanged::class, $event);
            $this->assertEquals(3, $event->getAmount());
            $this->assertEquals($orderItem->getOrderItemUuid()->toString(), $event->getOrderItemUuid()->toString());
        });
    }

    public function test_changeOrderItemAmount_should_do_nothing_when_amount_is_the_same(): void
    {
        $orderItem = $this->orderItem(2);
        $order = new Order(
            Uuid::uuid4(),
            Uuid::uuid4(),
            $this->inventory(),
            [$orderItem],
        );
        $order->changeOrderItemAmount($orderItem->getOrderItemUuid(), 2);
        $this->assertCount(0, $order->popNewEvents());
    }

    public function test_changeOrderItemAmount_should_throw_exception_when_order_item_is_not_found(): void
    {
        $order = new Order(
            Uuid::uuid4(),
            Uuid::uuid4(),
            $this->inventory(),
            [],
        );
        $this->expectException(OrderItemException::class);
        $order->changeOrderItemAmount(Uuid::uuid4(), 3);
    }

    public function test_removeOrderItemAmount_should_trigger_an_event(): void
    {
        $orderItem = $this->orderItem(2);
        $order = new Order(
            Uuid::uuid4(),
            Uuid::uuid4(),
            $this->inventory(),
            [$orderItem, $this->orderItem(1)],
        );
        $order->removeOrderItem($orderItem->getOrderItemUuid());
        $this->assertCount(1, $order->getOrderItems());//Started with two, one removed
        $this->assertEvent($order->popNewEvents(), 1, function ($event) use ($orderItem) {
            $this->assertInstanceOf(OrderItemRemoved::class, $event);
            $this->assertEquals($orderItem->getOrderItemUuid()->toString(), $event->getOrderItemUuid()->toString());
        });
    }

    public function test_removeOrderItemAmount_should_throw_exception_when_order_item_is_not_found(): void
    {
        $order = new Order(
            Uuid::uuid4(),
            Uuid::uuid4(),
            $this->inventory(),
            [$this->orderItem(2), $this->orderItem(1)],
        );
        $this->expectException(OrderItemException::class);
        $order->removeOrderItem(Uuid::uuid4());
    }

    private function inventory(Price $price = null): InventoryInterface
    {
        return \Mockery::mock(InventoryInterface::class, [
            'inventoryExists' => true,
            'getCurrentItemPrice' => $price ?? new Price(0),
        ]);
    }

    private function emptyInventory(): InventoryInterface
    {
        $inventory = \Mockery::mock(InventoryInterface::class, [
            'inventoryExists' => true,
        ]);
        $inventory->shouldReceive('getCurrentItemPrice')
            ->andThrow(new InventoryException("Item not found"))
        ;
        return $inventory;
    }

    private function orderItem(int $amount = 1, Price $price = null): OrderItem
    {
        return new OrderItem(Uuid::uuid4(), Uuid::uuid4(), $price ?? new Price(0), $amount);
    }
}
