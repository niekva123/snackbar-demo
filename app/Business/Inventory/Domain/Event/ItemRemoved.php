<?php
declare(strict_types=1);

namespace App\Business\Inventory\Domain\Event;

use App\Business\EventInterface;
use Ramsey\Uuid\UuidInterface;

class ItemRemoved implements EventInterface
{
    public function __construct(
        private readonly UuidInterface $uuid,
    ) {}

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }
}
