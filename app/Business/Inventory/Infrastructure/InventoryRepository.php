<?php
declare(strict_types=1);

namespace App\Business\Inventory\Infrastructure;

use App\Business\Inventory\Domain\Entity\Inventory;
use App\Business\Inventory\Domain\Entity\Item;
use App\Business\Inventory\Domain\Event\ItemAdded;
use App\Business\Inventory\Domain\Repository\InventoryRepositoryInterface;
use App\Business\Value\Price;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function getInventory(UuidInterface $snackBarUuid): Inventory
    {
        $items = \App\Models\Item::whereSnackbarUuid((string) $snackBarUuid)
            ->get()
            ->map(fn (\App\Models\Item $item) => new Item(
                Uuid::fromString($item->uuid),
                $item->name,
                new Price($item->price),
            ))
        ;

        return new Inventory(
            $snackBarUuid,
            $items,
        );
    }

    public function save(Inventory $inventory): void
    {
        $events = $inventory->popNewEvents();
        foreach ($events as $event) {
            match (get_class($event)) {
                ItemAdded::class => $this->handleItemAdded($inventory, $event),
                default => throw new \LogicException("Event " . get_class($event) . " not implemented in " . __METHOD__),
            };

            //Save done, dispatch event
            event($event);
        }
    }

    private function handleItemAdded(Inventory $inventory, ItemAdded $event): void
    {
        $item = new \App\Models\Item();
        $item->uuid = (string) $event->getUuid();
        $item->name = $event->getName();
        $item->price = $event->getPrice()->getPriceInCents();
        $item->snackbar_uuid = (string) $inventory->getSnackBarUuid();
        $item->saveOrFail();
    }
}
