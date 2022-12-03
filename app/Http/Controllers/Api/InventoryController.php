<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Business\Inventory\Domain\Service\InventoryService;
use App\Business\Value\Price;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class InventoryController extends JsonAPIController
{
    public function __construct(
        private readonly InventoryService $inventoryService,
    ) {}

    public function createInventoryItem(Request $request, string $snackbarUuid): ItemResource|Response
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->failedResponse('Validation failed', 400, $validator->messages()->toArray());
        }
        $uuid = $this->inventoryService->createItem(Uuid::fromString($snackbarUuid), $data['name'], new Price((int) $data['price']));

        return new ItemResource(Item::findOrFail($uuid->toString()));
    }

    public function changeInventoryItem(Request $request, string $snackbarUuid, string $itemUuid): ItemResource|Response
    {
        $data = json_decode($request->getContent(), true);
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->failedResponse('Validation failed', 400, $validator->messages()->toArray());
        }
        $this->inventoryService->changeItem(Uuid::fromString($snackbarUuid), Uuid::fromString($itemUuid), $data['name'], new Price((int) $data['price']));

        return new ItemResource(Item::findOrFail($itemUuid));
    }

    public function removeInventoryItem(string $snackbarUuid, string $itemUuid): Response
    {
        $this->inventoryService->removeItem(Uuid::fromString($snackbarUuid), Uuid::fromString($itemUuid));
        return $this->response([
            'success' => true,
        ]);
    }

    public function getInventoryItems(string $snackbarUuid): ResourceCollection
    {
        return ItemResource::collection(Item::whereSnackbarUuid($snackbarUuid)->get());
    }
}
