<?php
declare(strict_types=1);

namespace App\Business;

abstract class AggregateRoot implements TriggersEventsInterface
{
    private array $newEvents = [];

    public function newEvent(EventInterface $event): void
    {
        $this->newEvents[] = $event;
    }

    public function popNewEvents(): array
    {
        $events = $this->newEvents;
        $this->newEvents = [];
        return $events;
    }
}
