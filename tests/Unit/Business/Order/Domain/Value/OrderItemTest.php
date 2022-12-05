<?php

namespace Tests\Unit\Business\Order\Domain\Value;

use App\Business\Order\Domain\Exception\OrderItemException;
use App\Business\Order\Domain\Value\OrderItem;
use App\Business\Value\Price;
use Ramsey\Uuid\Uuid;
use Tests\UnitTestCase;

class OrderItemTest extends UnitTestCase
{
    public function test_OrderItem_setters_and_getters(): void
    {
        $orderItemUuid = Uuid::uuid4();
        $itemUuid = Uuid::uuid4();
        $orderItem = new OrderItem(
            $orderItemUuid,
            $itemUuid,
            new Price(150),
            1,
        );

        $this->assertEquals($orderItemUuid->toString(), $orderItem->getOrderItemUuid()->toString());
        $this->assertEquals($itemUuid->toString(), $orderItem->getItemUuid()->toString());
        $this->assertEquals(150, $orderItem->getPricePerUnit()->getPriceInCents());
        $this->assertEquals(1, $orderItem->getAmount());

        $orderItem->changeAmount(20);
        $this->assertEquals(20, $orderItem->getAmount());
    }

    /**
     * @dataProvider invalidAmounts
     */
    public function test_OrderItem_cannot_be_created_with_invalid_amounts(int $invalidAmount): void
    {
        $this->expectException(OrderItemException::class);
        new OrderItem(
            Uuid::uuid4(),
            Uuid::uuid4(),
            new Price(150),
            $invalidAmount,
        );
    }

    /**
     * @dataProvider invalidAmounts
     */
    public function test_changeAmount_throws_exception_when_order_amount_is_invalid(int $invalidAmount): void
    {
        $orderItem = new OrderItem(
            Uuid::uuid4(),
            Uuid::uuid4(),
            new Price(150),
            1,
        );

        $this->expectException(OrderItemException::class);
        $orderItem->changeAmount($invalidAmount);
    }

    private function invalidAmounts(): array
    {
        return [
            [0],
            [21],
        ];
    }
}
