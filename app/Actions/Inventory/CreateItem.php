<?php

namespace App\Actions\Inventory;

use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Models\Snackbar;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateItem
{
    use AsAction;

    public function handle(Snackbar $snackbar, array $data): Item
    {
        return $snackbar->items()->create($data);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'price' => 'required|numeric',
        ];
    }

    public function asController(ActionRequest $request, Snackbar $snackbar): ItemResource
    {
        $data = $request->only('name', 'price');
        $item = $this->handle($snackbar, $data);
        return new ItemResource($item);
    }
}
