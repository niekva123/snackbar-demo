<?php
declare(strict_types=1);

namespace App\Business\Inventory\Domain\Repository;

use App\Business\Inventory\Domain\Entity\Inventory;
use Ramsey\Uuid\UuidInterface;

interface InventoryRepositoryInterface
{
    public function getInventory(UuidInterface $snackBarUuid): Inventory;

    public function save(Inventory $inventory): void;
}
