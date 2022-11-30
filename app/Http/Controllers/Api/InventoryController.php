<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Business\Inventory\Domain\Service\InventoryService;
use App\Business\Value\Price;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Ramsey\Uuid\Uuid;

class InventoryController extends Controller
{
    public function __construct(
        private readonly InventoryService $inventoryService,
    ) {}

    public function addInventoryItem(Request $request): Response
    {
        $data = $request->validate([
            'snackbar_uuid' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        $uuid = $this->inventoryService->addItem(Uuid::fromString($data['uuid']), $data['name'], new Price($data['price']));

        return \response(json_encode([
            'uuid' => $uuid,
            'name' => $data['name'],
            'price' => $data['price'],
        ]), 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
