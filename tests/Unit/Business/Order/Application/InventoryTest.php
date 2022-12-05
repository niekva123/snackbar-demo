<?php

namespace Tests\Unit\Business\Order\Application;

use App\Business\Inventory\Domain\Entity\Inventory as InventoryEntity;
use App\Business\Inventory\Domain\Entity\Item;
use App\Business\Inventory\Domain\Exception\InventoryException;
use App\Business\Inventory\Domain\Repository\InventoryRepositoryInterface;
use App\Business\Order\Application\Inventory;
use App\Business\Value\Price;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tests\UnitTestCase;

class InventoryTest extends UnitTestCase
{
    public function test_inventoryExists_should_return_true_when_snackbar_exists(): void
    {
        $existingUuid = Uuid::uuid4();
        $inventory = new Inventory($this->repositoryMock($existingUuid));
        $this->assertTrue($inventory->inventoryExists($existingUuid));
    }

    public function test_inventoryExists_should_return_false_when_snackbar_doenst_exists(): void
    {
        $inventory = new Inventory($this->repositoryMock(Uuid::uuid4()));
        $this->assertFalse($inventory->inventoryExists(Uuid::uuid4()));
    }

    public function test_getItemPrice_should_return_price(): void
    {
        $item = $this->itemMock(new Price(150));
        $snackbarUuid = Uuid::uuid4();
        $inventory = new Inventory($this->repositoryMock($snackbarUuid, $item));
        $price = $inventory->getCurrentItemPrice($snackbarUuid, $item->getUuid());
        $this->assertEquals(150, $price->getPriceInCents());
    }

    private function repositoryMock(UuidInterface $existingUuid, Item $item = null): InventoryRepositoryInterface
    {
        $repository = \Mockery::mock(InventoryRepositoryInterface::class);
        $repository->shouldReceive('getInventory')
            ->andReturnUsing(function (UuidInterface $requestedSnackbarUuid) use ($existingUuid) {
                if ($requestedSnackbarUuid->toString() !== $existingUuid->toString()) {
                    throw InventoryException::notFound($requestedSnackbarUuid);
                }
                return $this->inventoryMock();
            })
        ;
        $repository->shouldNotReceive('save');
        return $repository;
    }

    private function inventoryMock(): InventoryEntity
    {
        return \Mockery::mock(InventoryEntity::class);
    }

    private function itemMock(Price $price): Item
    {
        return new Item(Uuid::uuid4(), 'mock', $price);
    }
}
