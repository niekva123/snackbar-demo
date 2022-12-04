<?php

namespace Tests\Unit\Business\Value;

use App\Business\Value\Price;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function test_getPriceInCents(): void
    {
        $price = new Price(150);
        $this->assertEquals(150, $price->getPriceInCents());
    }

    public function test_getPriceInEuros(): void
    {
        $price = new Price(150);
        $this->assertEquals(1.50, $price->getPriceInEuro());
    }

    public function test_getEuroAmount(): void
    {
        $price = new Price(150);
        $this->assertEquals(1, $price->getEuroAmount());
    }

    public function test_getCentAmount(): void
    {
        $price = new Price(150);
        $this->assertEquals(50, $price->getCentAmount());
    }

    public function test_price_cannot_be_created_with_negative_amount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Price(-1);
    }

    public function test_empty_price(): void
    {
        $price = new Price(0);
        $this->assertEquals(0, $price->getCentAmount());
        $this->assertEquals(0, $price->getEuroAmount());
        $this->assertEquals(0, $price->getPriceInEuro());
        $this->assertEquals(0, $price->getPriceInCents());
    }
}
