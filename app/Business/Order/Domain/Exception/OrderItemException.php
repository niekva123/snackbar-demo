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
}
