<?php
declare(strict_types=1);

namespace App\Business\Order\Application;

use App\Business\Inventory\Domain\Exception\InventoryException;
use App\Business\Inventory\Domain\Exception\ItemNotFoundException;
use App\Business\Inventory\Domain\Repository\InventoryRepositoryInterface;
use App\Business\Order\Domain\Exception\InventoryException as OrderInventoryExceptionAlias;
use App\Business\Order\Domain\Service\InventoryInterface;
use App\Business\Value\Price;
use Ramsey\Uuid\UuidInterface;

class Inventory implements InventoryInterface
{
    public function __construct(
        private readonly InventoryRepositoryInterface $inventoryRepository,
    ) {}

    public function getCurrentItemPrice(UuidInterface $snackbarUuid, UuidInterface $itemUuid): Price
    {
        try {
            $inventory = $this->inventoryRepository->getInventory($snackbarUuid);
            return $inventory->getItem($itemUuid)->getPrice();
        } catch (InventoryException) {
            throw OrderInventoryExceptionAlias::forInventoryNotFound($snackbarUuid);
        } catch (ItemNotFoundException) {
            throw OrderInventoryExceptionAlias::forItemNotFoundInInventory($snackbarUuid, $itemUuid);
        }
    }

    public function inventoryExists(UuidInterface $snackbarUuid): bool
    {
        try {
            $this->inventoryRepository->getInventory($snackbarUuid);
            return true;
        } catch (InventoryException) {
            return false;
        }
    }
}
