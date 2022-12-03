<?php
declare(strict_types=1);

namespace App\Business\Order\Application;

use App\Business\Order\Domain\Repository\OrderRepositoryInterface;
use App\Business\Order\Domain\Service\InventoryInterface;
use App\Business\Order\Infrastructure\OrderRepository;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(InventoryInterface::class, Inventory::class);
    }
}
