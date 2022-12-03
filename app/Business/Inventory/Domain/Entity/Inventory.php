<?php
declare(strict_types=1);

namespace App\Business\Inventory\Domain\Entity;

use App\Business\AggregateRoot;
use App\Business\Inventory\Domain\Event\ItemChanged;
use App\Business\Inventory\Domain\Event\ItemCreated;
use App\Business\Inventory\Domain\Event\ItemRemoved;
use App\Business\Inventory\Domain\Exception\ItemNameException;
use App\Business\Inventory\Domain\Exception\ItemNotFoundException;
use App\Business\Value\Price;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Inventory extends AggregateRoot
{
    /**
     * @var Item[]
     */
    private array $items;

    /**
     * @param Item[] $items
     */
    public function __construct(
        private readonly UuidInterface $snackBarUuid,
        array $items,
    ) {
        $this->items = [];
        //Make items better accessable by uuid
        foreach ($items as $item) {
            $this->items[$item->getUuid()->toString()] = $item;
        }
    }

    public function createItem(string $name, Price $price): UuidInterface
    {
        $this->nameShouldNotExistYet($name);

        $uuid = Uuid::uuid4();
        $this->items[] = new Item($uuid, $name, $price);

        $this->newEvent(new ItemCreated($uuid, $name, $price));
        return $uuid;
    }

    public function changeItem(UuidInterface $uuid, string $name, Price $price): void
    {
        $item = $this->getItem($uuid);
        if ($item->getName() === $name && $item->getPrice()->getPriceInCents() === $price->getPriceInCents()) {
            return;//Nothing changed. No problem, no updates
        }
        $item->setName($name);
        $item->setPrice($price);

        $this->newEvent(new ItemChanged($uuid, $name, $price));
    }

    public function removeItem(UuidInterface $uuid): void
    {
        //Make sure item exists
        $this->getItem($uuid);
        unset($this->items[$uuid->toString()]);

        $this->newEvent(new ItemRemoved($uuid));
    }

    public function getSnackBarUuid(): UuidInterface
    {
        return $this->snackBarUuid;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getItem(UuidInterface $uuid): Item
    {
        return $this->items[$uuid->toString()] ?? throw ItemNotFoundException::forUnknownUuid($uuid);
    }

    private function nameShouldNotExistYet(string $name): void
    {
        $names = array_map(fn (Item $item) => $item->getName(), $this->items);
        if (in_array($name, $names, true)) {
            throw ItemNameException::forDuplicateName($name);
        }
    }
}
