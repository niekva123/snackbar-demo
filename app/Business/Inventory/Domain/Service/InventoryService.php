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

    public function addItem(UuidInterface $snackBarUuid, string $itemName, Price $itemPrice): UuidInterface
    {
        $inventory = $this->inventoryRepository->getInventory($snackBarUuid);

        $itemUuid = $inventory->addItem($itemName, $itemPrice);
        $this->inventoryRepository->save($inventory);

        return $itemUuid;
    }
}
