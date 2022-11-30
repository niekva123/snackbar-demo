<?php
declare(strict_types=1);

namespace App\Business\Inventory\Application;

use App\Business\Inventory\Domain\Repository\InventoryRepositoryInterface;
use App\Business\Inventory\Infrastructure\InventoryRepository;
use Illuminate\Support\ServiceProvider;

class InventoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(InventoryRepositoryInterface::class, InventoryRepository::class);
    }
}
