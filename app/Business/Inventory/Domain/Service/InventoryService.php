<?php
declare(strict_types=1);

namespace App\Business\Inventory\Domain\Service;

use App\Business\Inventory\Domain\Repository\InventoryRepositoryInterface;
use App\Business\Value\Price;
use Ramsey\Uuid\UuidInterface;

class InventoryService
{
    public function __construct(
        private readonly InventoryRepositoryInterface $inventoryRepository,
    ) {}

    public function createItem(UuidInterface $snackBarUuid, string $itemName, Price $itemPrice): UuidInterface
    {
        $inventory = $this->inventoryRepository->getInventory($snackBarUuid);

        $itemUuid = $inventory->createItem($itemName, $itemPrice);
        $this->inventoryRepository->save($inventory);

        return $itemUuid;
    }

    public function changeItem(UuidInterface $snackBarUuid, UuidInterface $itemUuid, string $itemName, Price $itemPrice): void
    {
        $inventory = $this->inventoryRepository->getInventory($snackBarUuid);

        $inventory->changeItem($itemUuid, $itemName, $itemPrice);
        $this->inventoryRepository->save($inventory);
    }

    public function removeItem(UuidInterface $snackBarUuid, UuidInterface $itemUuid): void
    {
        $inventory = $this->inventoryRepository->getInventory($snackBarUuid);

        $inventory->removeItem($itemUuid);
        $this->inventoryRepository->save($inventory);
    }
}
