<?php

namespace Tests\Unit\Business\Inventory\Domain\Entity;

use App\Business\EventInterface;
use App\Business\Inventory\Domain\Entity\Inventory;
use App\Business\Inventory\Domain\Entity\Item;
use App\Business\Inventory\Domain\Event\ItemChanged;
use App\Business\Inventory\Domain\Event\ItemCreated;
use App\Business\Inventory\Domain\Event\ItemRemoved;
use App\Business\Inventory\Domain\Exception\ItemNameException;
use App\Business\Inventory\Domain\Exception\ItemNotFoundException;
use App\Business\Value\Price;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tests\UnitTestCase;

class InventoryTest extends UnitTestCase
{
    public function test_addItems_should_trigger_an_event(): void
    {
        $inventory = new Inventory(
            Uuid::uuid4(),
            [],
        );
        $newUuid = $inventory->createItem("Potatoes", new Price(225));
        $this->assertInstanceOf(UuidInterface::class, $newUuid);
        $this->assertCount(1, $inventory->getItems());

        $this->assertEvent($inventory->popNewEvents(), 1, function (EventInterface $event) {
            $this->assertInstanceOf(ItemCreated::class, $event);
            $this->assertEquals("Potatoes", $event->getName());
            $this->assertEquals(225, $event->getPrice()->getPriceInCents());
        });
    }

    public function test_addItems_should_throw_an_exception_when_item_name_already_exists(): void
    {
        $inventory = new Inventory(
            Uuid::uuid4(),
            [$this->item("Potatoes")],
        );
        $this->expectException(ItemNameException::class);
        $inventory->createItem("Potatoes", new Price(225));
    }

    public function test_changeItem_should_trigger_an_event(): void
    {
        $potatoes = $this->item('Potatoes', new Price(150));
        $inventory = new Inventory(
            Uuid::uuid4(),
            [$potatoes],
        );
        $inventory->changeItem($potatoes->getUuid(), 'Pommes Frites', new Price(175));
        $this->assertEquals("Pommes Frites", $inventory->getItem($potatoes->getUuid())->getName());
        $this->assertEquals(175, $inventory->getItem($potatoes->getUuid())->getPrice()->getPriceInCents());

        $this->assertEvent($inventory->popNewEvents(), 1, function (EventInterface $event) {
            $this->assertInstanceOf(ItemChanged::class, $event);
            $this->assertEquals(175, $event->getPrice()->getPriceInCents());
            $this->assertEquals('Pommes Frites', $event->getName());
        });
    }

    public function test_changeItem_should_do_nothing_when_nothing_is_changed(): void
    {
        $potatoes = $this->item('Potatoes', new Price(150));
        $inventory = new Inventory(
            Uuid::uuid4(),
            [$potatoes],
        );
        $inventory->changeItem($potatoes->getUuid(), 'Potatoes', new Price(150));
        $this->assertCount(0, $inventory->popNewEvents());
    }

    public function test_changeItem_should_throw_exception_when_item_is_not_found(): void
    {
        $inventory = new Inventory(
            Uuid::uuid4(),
            [$this->item('Potatoes')],
        );
        $this->expectException(ItemNotFoundException::class);
        $inventory->changeItem(Uuid::uuid4(), 'name', new Price(0));
    }

    public function test_removeItem_should_trigger_an_event(): void
    {
        $potatoes = $this->item('Potatoes');
        $inventory = new Inventory(
            Uuid::uuid4(),
            [$potatoes],
        );
        $inventory->removeItem($potatoes->getUuid());
        $this->assertCount(0, $inventory->getItems());
        $this->assertEvent($inventory->popNewEvents(), 1, function (EventInterface $event) use ($potatoes) {
            $this->assertInstanceOf(ItemRemoved::class, $event);
            $this->assertEquals($potatoes->getUuid()->toString(), $event->getUuid()->toString());
        });
    }

    public function test_removeItem_should_throw_exception_when_item_not_found(): void
    {
        $inventory = new Inventory(
            Uuid::uuid4(),
            [$this->item('Potatoes')],
        );
        $this->expectException(ItemNotFoundException::class);
        $inventory->removeItem(Uuid::uuid4());
    }

    private function item(string $itemName, Price $price = null): Item
    {
        return new Item(Uuid::uuid4(), $itemName, $price ?? new Price(0));
    }
}
