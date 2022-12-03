<?php
declare(strict_types=1);

namespace App\Business\Inventory\Infrastructure;

use App\Business\Inventory\Domain\Entity\Inventory;
use App\Business\Inventory\Domain\Entity\Item;
use App\Business\Inventory\Domain\Event\ItemChanged;
use App\Business\Inventory\Domain\Event\ItemCreated;
use App\Business\Inventory\Domain\Event\ItemRemoved;
use App\Business\Inventory\Domain\Exception\InventoryException;
use App\Business\Inventory\Domain\Repository\InventoryRepositoryInterface;
use App\Business\Value\Price;
use App\Models\Snackbar;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Events\Dispatcher;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class InventoryRepository implements InventoryRepositoryInterface
{
    public function __construct(
        private readonly Dispatcher $eventDispatcher,
    ) {}

    public function getInventory(UuidInterface $snackbarUuid): Inventory
    {
        try {
            $snackbar = Snackbar::whereUuid($snackbarUuid)
                ->with('items')
                ->firstOrFail()
            ;
        } catch (ModelNotFoundException) {
            throw InventoryException::notFound($snackbarUuid);
        }

        $items = $snackbar->items
            ->map(fn (\App\Models\Item $item) => new Item(
                Uuid::fromString($item->uuid),
                $item->name,
                new Price($item->price),
            ))
        ;

        return new Inventory(
            $snackbarUuid,
            $items->toArray(),
        );
    }

    public function save(Inventory $inventory): void
    {
        $events = $inventory->popNewEvents();
        foreach ($events as $event) {
            match (get_class($event)) {
                ItemCreated::class => $this->handleItemAdded($inventory, $event),
                ItemChanged::class => $this->handleItemChanged($event),
                ItemRemoved::class => $this->handleItemRemoved($event),
                default => throw new \LogicException("Event " . get_class($event) . " not implemented in " . __METHOD__),
            };

            $this->eventDispatcher->dispatch($event);
        }
    }

    private function handleItemAdded(Inventory $inventory, ItemCreated $event): void
    {
        $item = new \App\Models\Item();
        $item->uuid = (string) $event->getUuid();
        $item->name = $event->getName();
        $item->price = $event->getPrice()->getPriceInCents();
        $item->snackbar_uuid = (string) $inventory->getSnackBarUuid();
        $item->saveOrFail();
    }

    private function handleItemChanged(ItemChanged $event): void
    {
        $item = \App\Models\Item::findOrFail($event->getUuid()->toString());
        $item->name = $event->getName();
        $item->price = $event->getPrice()->getPriceInCents();
        $item->saveOrFail();
    }

    private function handleItemRemoved(ItemRemoved $event): void
    {
        \App\Models\Item::whereUuid($event->getUuid()->toString())->delete();
    }
}
