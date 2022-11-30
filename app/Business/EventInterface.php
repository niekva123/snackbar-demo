<?php
declare(strict_types=1);

namespace App\Business;

interface EventInterface extends \JsonSerializable
{
    public static function fromJson(array $data): self;
}
