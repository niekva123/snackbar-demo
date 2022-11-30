<?php
declare(strict_types=1);

namespace App\Business\Value;

class Price
{
    public function __construct(
        private readonly int $priceInCents,
    ) {
        if ($this->priceInCents < 0) {
            throw new \InvalidArgumentException("Negative price given, should be 0 or higher");
        }
    }

    public function getPriceInCents(): int
    {
        return $this->priceInCents;
    }
}
