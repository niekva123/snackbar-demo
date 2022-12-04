<?php
declare(strict_types=1);

namespace Tests;

use Mockery\Adapter\Phpunit\MockeryTestCase;

class UnitTestCase extends MockeryTestCase
{
    protected function assertEvent(array $events, int $expectedEventCount, callable $eventAssert): void
    {
        $this->assertCount($expectedEventCount, $events);
        foreach ($events as $event) {
            call_user_func_array($eventAssert, [$event]);
        }
    }
}
