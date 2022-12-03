<?php

namespace Tests\Unit\Business\Inventory\Domain\Entity;

use App\Business\Inventory\Domain\Entity\Inventory;
use App\Business\Inventory\Domain\Entity\Item;
use App\Business\Inventory\Domain\Event\ItemCreated;
use App\Business\Inventory\Domain\Exception\ItemNameException;
use App\Business\Value\Price;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class InventoryTest extends MockeryTestCase
{
    public function test_addItems_should_trigger_an_event()
    {
        $inventory = new Inventory(
            Uuid::uuid4(),
            [],
        );
        $newUuid = $inventory->createItem("Potatoes", new Price(225));
        $this->assertInstanceOf(UuidInterface::class, $newUuid);
        $this->assertCount(1, $inventory->getItems());

        $events = $inventory->popNewEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(ItemCreated::class, $events[0]);
    }

    public function test_addItems_should_throw_an_exception_when_item_name_already_exists()
    {
        $inventory = new Inventory(
            Uuid::uuid4(),
            [$this->item("Potatoes")],
        );
        $this->expectException(ItemNameException::class);
        $inventory->createItem("Potatoes", new Price(225));
    }

    private function item(string $itemName): Item
    {
        return \Mockery::mock(Item::class, [
            'getName' => $itemName,
        ]);
    }
}
