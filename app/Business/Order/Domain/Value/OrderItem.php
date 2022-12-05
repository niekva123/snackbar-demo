<?php
declare(strict_types=1);

namespace App\Business\Order\Domain\Value;

use App\Business\Order\Domain\Exception\OrderItemException;
use App\Business\Value\Price;
use Ramsey\Uuid\UuidInterface;

class OrderItem
{
    private const MAX_AMOUNT = 20;

    public function __construct(
        private readonly UuidInterface $orderItemUuid,
        private readonly UuidInterface $itemUuid,
        private readonly Price $pricePerUnit,
        private int $amount,
    ) {
        $this->amountShouldBeHigherThanZero($this->amount);
    }

    public function getOrderItemUuid(): UuidInterface
    {
        return $this->orderItemUuid;
    }

    public function getItemUuid(): UuidInterface
    {
        return $this->itemUuid;
    }

    public function getPricePerUnit(): Price
    {
        return $this->pricePerUnit;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function changeAmount(int $newAmount): void
    {
        $this->amountShouldBeHigherThanZero($newAmount);
        $this->amount = $newAmount;
    }

    private function amountShouldBeHigherThanZero(int $amount): void
    {
        if ($amount <= 0) {
            throw OrderItemException::forAmountTooLow($amount);
        }
        if ($amount > self::MAX_AMOUNT) {
            throw OrderItemException::forAmountTooHigh($amount, self::MAX_AMOUNT);
        }
    }
}
