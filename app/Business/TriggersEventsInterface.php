<?php
declare(strict_types=1);

namespace App\Business;

interface TriggersEventsInterface
{
    /**
     * Add new event
     */
    public function newEvent(EventInterface $event): void;

    /**
     * Get all new events and reset the events
     *
     * @return EventInterface[]
     */
    public function popNewEvents(): array;
}
