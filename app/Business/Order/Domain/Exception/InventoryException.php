<?php
declare(strict_types=1);

namespace App\Business\Order\Domain\Exception;

use Ramsey\Uuid\UuidInterface;

class InventoryException extends \DomainException
{
    public static function forInventoryNotFound(UuidInterface $snackbarUuid): self
    {
        return new self("Snackbar $snackbarUuid not found");
    }

    public static function forItemNotFoundInInventory(UuidInterface $snackbarUuid, UuidInterface $itemUuid): self
    {
        return new self("Item $itemUuid not found in inventory $snackbarUuid");
    }
}
