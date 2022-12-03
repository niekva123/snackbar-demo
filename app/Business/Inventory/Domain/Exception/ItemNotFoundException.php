<?php
declare(strict_types=1);

namespace App\Business\Inventory\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class ItemNotFoundException extends \DomainException
{
    public static function forUnknownUuid(UuidInterface $uuid): self
    {
        return new self("No item found with uuid " . $uuid->toString());
    }
}
