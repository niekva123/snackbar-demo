<?php
declare(strict_types=1);

namespace App\Business\Order\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class OrderItemException extends \DomainException
{
    public static function notFound(UuidInterface $uuid): self
    {
        return new self("No order item found with uuid " . $uuid->toString());
    }

    public static function forAmountTooLow(int $amount): self
    {
        return new self("Order amount $amount is too low, should be higher than 0");
    }

    public static function forAmountTooHigh(int $amount, int $maxAmount): self
    {
        return new self("Order amount $amount is too high, max $maxAmount allowed");
    }
}
