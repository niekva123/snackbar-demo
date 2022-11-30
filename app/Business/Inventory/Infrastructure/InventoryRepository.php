<?php
declare(strict_types=1);

namespace App\Business\Inventory\Infrastructure;

use App\Business\Inventory\Domain\Entity\Inventory;
use App\Business\Inventory\Domain\Repository\InventoryRepositoryInterface;
use Ramsey\Uuid\UuidInterface;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function getInventory(UuidInterface $snackBarUuid): Inventory
    {
        //TODO
    }

    public function save(Inventory $inventory): void
    {
        //TODO
    }
}
