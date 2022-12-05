<?php
declare(strict_types=1);

namespace App\Business\Order\Domain\Service;

use App\Business\Order\Domain\Exception\InventoryException;
use App\Business\Value\Price;
use Ramsey\Uuid\UuidInterface;

interface InventoryInterface
{
    public function inventoryExists(UuidInterface $snackbarUuid): bool;

    /**
     * @throws InventoryException
     */
    public function getCurrentItemPrice(UuidInterface $snackbarUuid, UuidInterface $itemUuid): Price;
}
