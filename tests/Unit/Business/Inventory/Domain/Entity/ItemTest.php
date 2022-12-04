<?php

namespace Tests\Unit\Business\Inventory\Domain\Entity;

use App\Business\Inventory\Domain\Entity\Item;
use App\Business\Inventory\Domain\Exception\ItemNameException;
use App\Business\Value\Price;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ItemTest extends TestCase
{
    public function test_Item_setters_and_getters(): void
    {
        $uuid = Uuid::uuid4();
        $item = new Item(
            $uuid,
            "Potatoes",
            new Price(150),
        );
        $this->assertEquals($uuid->toString(), $item->getUuid());
        $this->assertEquals("Potatoes", $item->getName());
        $this->assertEquals(150, $item->getPrice()->getPriceInCents());

        $item->setName("Pommes Frites");
        $item->setPrice(new Price(164));
        $this->assertEquals("Pommes Frites", $item->getName());
        $this->assertEquals(164, $item->getPrice()->getPriceInCents());
    }

    public function test_Item_cannot_be_created_with_empty_name(): void
    {
        $this->expectException(ItemNameException::class);
        new Item(Uuid::uuid4(), "", new Price(150));
    }
}
