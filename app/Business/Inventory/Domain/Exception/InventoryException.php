<?php
declare(strict_types=1);

namespace App\Business\Inventory\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class InventoryException extends \DomainException
{
    public static function notFound(UuidInterface $uuid): self
    {
        return new self("No snackbar or inventory found with uuid " . $uuid->toString());
    }
}
