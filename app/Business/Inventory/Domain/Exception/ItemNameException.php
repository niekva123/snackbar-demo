<?php
declare(strict_types=1);

namespace App\Business\Inventory\Domain\Exception;

class ItemNameException extends \DomainException
{
    public static function forDuplicateName(string $name): self
    {
        return new self("Name $name already exists");
    }
}
