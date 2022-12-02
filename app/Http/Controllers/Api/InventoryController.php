<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Business\Inventory\Domain\Service\InventoryService;
use App\Business\Value\Price;
use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class InventoryController extends Controller
{
    public function __construct(
        private readonly InventoryService $inventoryService,
    ) {}

    public function addInventoryItem(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, [
            'snackbar_uuid' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return \response(json_encode([
                "error" => 'Validation failed',
                "details" => $validator->messages(),
            ]));
        }
        $uuid = $this->inventoryService->addItem(Uuid::fromString($data['snackbar_uuid']), $data['name'], new Price((int) $data['price']));

        return \response(json_encode([
            'uuid' => $uuid,
            'name' => $data['name'],
            'price' => $data['price'],
        ]), 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function getInventoryItems(string $snackbarUuid): ResourceCollection
    {
        return ItemResource::collection(Item::whereSnackbarUuid($snackbarUuid)->get());
    }
}
