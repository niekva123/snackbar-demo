<?php
declare(strict_types=1);

namespace App\Business\Inventory\Domain\Entity;

use App\Business\AggregateRoot;
use App\Business\Inventory\Domain\Event\ItemAdded;
use App\Business\Inventory\Domain\Exception\ItemNameException;
use App\Business\Value\Price;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Inventory extends AggregateRoot
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private readonly UuidInterface $snackBarUuid,
        private array $items,
    ) {}

    public function addItem(string $name, Price $price): UuidInterface
    {
        $this->nameShouldNotExistYet($name);

        $uuid = Uuid::uuid4();
        $this->items[] = new Item($uuid, $name, $price);

        $this->newEvent(new ItemAdded($uuid, $name, $price));
        return $uuid;
    }

    public function getSnackBarUuid(): UuidInterface
    {
        return $this->snackBarUuid;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    private function nameShouldNotExistYet(string $name): void
    {
        $names = array_map(fn (Item $item) => $item->getName(), $this->items);
        if (in_array($name, $names, true)) {
            throw ItemNameException::forDuplicateName($name);
        }
    }
}
