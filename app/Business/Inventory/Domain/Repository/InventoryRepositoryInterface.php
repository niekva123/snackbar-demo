<?php
declare(strict_types=1);

namespace App\Business\Inventory\Domain\Repository;

use App\Business\Inventory\Domain\Entity\Inventory;
use App\Business\Inventory\Domain\Exception\InventoryException;
use Ramsey\Uuid\UuidInterface;

interface InventoryRepositoryInterface
{
    /**
     * @throws InventoryException
     */
    public function getInventory(UuidInterface $snackbarUuid): Inventory;

    public function save(Inventory $inventory): void;
}
