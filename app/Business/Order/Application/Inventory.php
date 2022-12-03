<?php
declare(strict_types=1);

namespace App\Business\Order\Application;

use App\Business\Inventory\Domain\Repository\InventoryRepositoryInterface;
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
        $inventory = $this->inventoryRepository->getInventory($snackbarUuid);
        return $inventory->getItem($itemUuid)->getPrice();
    }

    public function inventoryExists(UuidInterface $snackbarUuid): bool
    {
        $inventory = $this->inventoryRepository->getInventory($snackbarUuid);
    }
}
